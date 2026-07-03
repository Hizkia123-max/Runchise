<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Database\Seeds\InitialDataSeeder;
use App\Modules\Inventory\Controllers\InventoryController;

/**
 * @internal
 */
final class InventoryTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $seed = InitialDataSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();
        ob_start();
        // Clear any leftover POST variables
        $_POST = [];
    }

    protected function tearDown(): void
    {
        $_POST = [];
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        parent::tearDown();
    }

    /**
     * Test that Stock Opname correctly updates stock quantity and
     * creates a wasted product log when physical stock is less than recorded.
     */
    public function testApplyOpnameReducesStockAndLogsWaste(): void
    {
        $db = \Config\Database::connect();

        // 1. Get an existing stock record from the seeded database
        $stock = $db->table('inventory_stocks')->get()->getRowArray();
        $this->assertNotEmpty($stock);

        $stockId = (int) $stock['id'];
        $originalQty = (float) $stock['quantity'];
        $productId = (int) $stock['product_id'];

        // We expect some initial stock quantity to reduce it
        $this->assertGreaterThan(0, $originalQty);

        // 2. Set up physical quantity less than recorded (e.g. reduce by 2)
        $physicalQty = (int) ($originalQty - 2);
        if ($physicalQty < 0) {
            $physicalQty = 0;
        }
        $lossQty = (int) ($originalQty - $physicalQty);

        $_POST['items'] = [
            $stockId => $physicalQty
        ];
        $_POST['reasons'] = [
            $stockId => 'Damaged during storage'
        ];

        // Re-initialize request to populate POST data
        $request = \Config\Services::request(null, false);
        $request->setGlobal('post', $_POST);
        
        $this->assertEquals([$stockId => $physicalQty], $request->getPost('items'));
        $this->assertEquals([$stockId => 'Damaged during storage'], $request->getPost('reasons'));

        $response = \Config\Services::response();
        $logger = \Config\Services::logger();

        $controller = new InventoryController();
        $controller->initController($request, $response, $logger);

        // 3. Execute applyOpname
        $result = $controller->applyOpname();

        // Verify it returns a redirect response
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $result);

        // 4. Verify stock in database is updated
        $updatedStock = $db->table('inventory_stocks')->where('id', $stockId)->get()->getRowArray();
        $this->assertEquals($physicalQty, (float) $updatedStock['quantity']);

        // 5. Verify wasted products log has been created
        $wasted = $db->table('wasted_products')
            ->where('product_id', $productId)
            ->where('branch_id', $stock['branch_id'])
            ->get()->getRowArray();

        $this->assertNotEmpty($wasted);
        $this->assertEquals($lossQty, (int) $wasted['quantity']);
        $this->assertEquals('Damaged during storage', $wasted['reason']);
    }

    /**
     * Test that Stock Opname correctly updates stock quantity and
     * DOES NOT create a wasted product log when physical stock is greater than or equal.
     */
    public function testApplyOpnameIncreasesStockWithoutWaste(): void
    {
        $db = \Config\Database::connect();

        $stock = $db->table('inventory_stocks')->get()->getRowArray();
        $this->assertNotEmpty($stock);

        $stockId = (int) $stock['id'];
        $originalQty = (float) $stock['quantity'];

        // Set physical quantity greater than recorded (e.g. increase by 5)
        $physicalQty = (int) ($originalQty + 5);

        $_POST['items'] = [
            $stockId => $physicalQty
        ];
        $_POST['reasons'] = [
            $stockId => 'Found extra during audit'
        ];

        $request = \Config\Services::request(null, false);
        $request->setGlobal('post', $_POST);
        $response = \Config\Services::response();
        $logger = \Config\Services::logger();

        $controller = new InventoryController();
        $controller->initController($request, $response, $logger);

        // Execute applyOpname
        $controller->applyOpname();

        // Verify stock in database is updated
        $updatedStock = $db->table('inventory_stocks')->where('id', $stockId)->get()->getRowArray();
        $this->assertEquals($physicalQty, (float) $updatedStock['quantity']);

        // Verify no new wasted products log is created for this increase
        $wastedCount = $db->table('wasted_products')
            ->where('product_id', $stock['product_id'])
            ->where('reason', 'Found extra during audit')
            ->countAllResults();

        $this->assertEquals(0, $wastedCount);
    }

    /**
     * Test that Stock Transfer correctly moves stock from source branch to target branch.
     */
    public function testApplyTransferMovesStockSuccessfully(): void
    {
        $db = \Config\Database::connect();

        // Ensure we have at least 2 branches
        $branches = $db->table('branches')->get()->getResultArray();
        if (count($branches) < 2) {
            // Seed a second branch
            $db->table('branches')->insert([
                'tenant_id'  => 1,
                'name'       => 'Sudirman Branch (Warehouse)',
                'address'    => 'Jl. Jenderal Sudirman No. 12, Jakarta',
                'phone'      => '021-98765432',
                'latitude'   => -6.21,
                'longitude'  => 106.81,
                'geo_radius' => 50,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $branches = $db->table('branches')->get()->getResultArray();
        }

        $fromBranchId = (int) $branches[0]['id'];
        $toBranchId = (int) $branches[1]['id'];

        // Find a product that has stock in the source branch
        $sourceStock = $db->table('inventory_stocks')
            ->where('branch_id', $fromBranchId)
            ->where('quantity >', 5)
            ->get()->getRowArray();

        $this->assertNotEmpty($sourceStock, 'Seed data needs a product with > 5 items in the first branch');

        $productId = (int) $sourceStock['product_id'];
        $sourceOriginalQty = (float) $sourceStock['quantity'];
        $transferQty = 3;

        // Get target branch stock before transfer (might not exist)
        $targetStockBefore = $db->table('inventory_stocks')
            ->where('branch_id', $toBranchId)
            ->where('product_id', $productId)
            ->get()->getRowArray();

        $targetOriginalQty = $targetStockBefore ? (float) $targetStockBefore['quantity'] : 0.0;

        // Prepare POST data
        $_POST['from_branch'] = $fromBranchId;
        $_POST['to_branch'] = $toBranchId;
        $_POST['items'] = [
            $productId => $transferQty
        ];

        $request = \Config\Services::request(null, false);
        $request->setGlobal('post', $_POST);
        $response = \Config\Services::response();
        $logger = \Config\Services::logger();

        $controller = new InventoryController();
        $controller->initController($request, $response, $logger);

        // Execute applyTransfer
        $result = $controller->applyTransfer();

        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $result);

        // Verify source branch stock decreased
        $sourceStockAfter = $db->table('inventory_stocks')
            ->where('branch_id', $fromBranchId)
            ->where('product_id', $productId)
            ->get()->getRowArray();
        $this->assertEquals($sourceOriginalQty - $transferQty, (float) $sourceStockAfter['quantity']);

        // Verify target branch stock increased
        $targetStockAfter = $db->table('inventory_stocks')
            ->where('branch_id', $toBranchId)
            ->where('product_id', $productId)
            ->get()->getRowArray();
        $this->assertNotEmpty($targetStockAfter);
        $this->assertEquals($targetOriginalQty + $transferQty, (float) $targetStockAfter['quantity']);
    }

    /**
     * Test that Stock Transfer fails and redirects back if source and target branches are identical.
     */
    public function testApplyTransferFailsForSameBranch(): void
    {
        $_POST['from_branch'] = 1;
        $_POST['to_branch'] = 1;
        $_POST['items'] = [
            1 => 5
        ];

        $request = \Config\Services::request(null, false);
        $request->setGlobal('post', $_POST);
        $response = \Config\Services::response();
        $logger = \Config\Services::logger();

        $controller = new InventoryController();
        $controller->initController($request, $response, $logger);

        $result = $controller->applyTransfer();

        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $result);
        // It redirects back (which is redirect()->back() or similar in CI4)
    }
}
