<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Database\Seeds\InitialDataSeeder;
use App\Modules\POS\Controllers\POSController;

/**
 * @internal
 */
final class POSReturnsTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $seed = InitialDataSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();
        ob_start();
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

    public function testProcessReturnCalculatesRefundAndAdjustsStock(): void
    {
        $db = \Config\Database::connect();

        // 1. Create a dummy transaction
        $tenantId = 1;
        $branchId = 1;
        
        $db->table('transactions')->insert([
            'tenant_id' => $tenantId,
            'branch_id' => $branchId,
            'pos_session_id' => 1,
            'invoice_number' => 'INV-TEST-RETURN-001',
            'subtotal' => 100000,
            'discount_amount' => 0,
            'tax_amount' => 11000,
            'total' => 111000,
            'payment_method' => 'Cash',
            'payment_status' => 'Paid',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $txId = $db->insertID();
        
        // Add dummy transaction item
        $db->table('transaction_items')->insert([
            'tenant_id' => $tenantId,
            'transaction_id' => $txId,
            'product_id' => 1,
            'quantity' => 2,
            'unit_price' => 50000,
            'discount_amount' => 0,
            'tax_amount' => 5500,
            'total' => 111000,
        ]);
        
        $txItemId = $db->insertID();
        
        // Ensure initial stock is seeded or exists
        $stockExist = $db->table('inventory_stocks')
            ->where('tenant_id', $tenantId)
            ->where('branch_id', $branchId)
            ->where('product_id', 1)
            ->get()->getRowArray();
            
        if ($stockExist) {
            $db->table('inventory_stocks')
                ->where('id', $stockExist['id'])
                ->update(['quantity' => 10]);
        } else {
            $db->table('inventory_stocks')->insert([
                'tenant_id' => $tenantId,
                'branch_id' => $branchId,
                'product_id' => 1,
                'quantity' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        // 2. Set up POST request for Return
        $_POST['transaction_id'] = $txId;
        $_POST['return_items'] = [
            $txItemId => 1 // Return 1 out of 2 items
        ];
        $_POST['return_reasons'] = [
            $txItemId => 'Barang Cacat / Rusak'
        ];
        $_POST['return_to_stock'] = [
            $txItemId => '1' // Yes, return to stock
        ];
        
        $request = \Config\Services::request(null, false);
        $request->setGlobal('post', $_POST);
        
        $response = \Config\Services::response();
        $logger = \Config\Services::logger();
        
        // Simulate Session data for active tenant
        $session = \Config\Services::session();
        $session->set('user_id', 1);
        $session->set('user_name', 'Admin Test');
        $session->set('user_role', 'TenantOwner');
        
        $controller = new POSController();
        $controller->initController($request, $response, $logger);
        
        // 3. Execute processReturn
        $result = $controller->processReturn();
        
        // Verify redirect
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $result);
        
        // 4. Verify stock is updated (original 10 + 1 returned = 11)
        $stock = $db->table('inventory_stocks')
            ->where('tenant_id', $tenantId)
            ->where('branch_id', $branchId)
            ->where('product_id', 1)
            ->get()->getRowArray();
        
        $this->assertEquals(11.0, (float) $stock['quantity']);
        
        // 5. Verify returns record exists
        $ret = $db->table('transaction_returns')
            ->where('transaction_id', $txId)
            ->get()->getRowArray();
        $this->assertNotEmpty($ret);
        // Refund amount should be half of the total (since we returned 1 out of 2)
        $this->assertEquals(55500.0, (float) $ret['total_refunded']);
        
        $retItem = $db->table('transaction_return_items')
            ->where('return_id', $ret['id'])
            ->get()->getRowArray();
        $this->assertNotEmpty($retItem);
        $this->assertEquals(1, (float) $retItem['quantity']);
        $this->assertEquals('Barang Cacat / Rusak', $retItem['reason']);
        $this->assertEquals(1, (int) $retItem['returned_to_stock']);
    }
}
