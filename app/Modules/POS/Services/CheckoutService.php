<?php

namespace App\Modules\POS\Services;

use App\Modules\POS\Models\TransactionModel;
use App\Modules\POS\Models\TransactionItemModel;
use App\Modules\Inventory\Models\InventoryStockModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class CheckoutService
{
    protected TransactionModel $txModel;
    protected TransactionItemModel $itemModel;

    public function __construct()
    {
        $this->txModel   = new TransactionModel();
        $this->itemModel = new TransactionItemModel();
    }

    public function process(array $input): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Calculate totals
            $subtotal = 0;
            $taxRate  = 0.11; // PPN 11%
            $items    = $input['items'] ?? [];

            foreach ($items as &$item) {
                $lineTotal = ($item['unit_price'] * $item['quantity']) - ($item['discount_amount'] ?? 0);
                $item['tax_amount'] = round($lineTotal * $taxRate, 2);
                $item['total']      = round($lineTotal + $item['tax_amount'], 2);
                $subtotal += $lineTotal;
            }

            $discountTotal = array_sum(array_column($items, 'discount_amount'));
            $taxTotal      = array_sum(array_column($items, 'tax_amount'));
            $grandTotal    = $subtotal + $taxTotal;
            $tenantId      = service('tenant')->getId();

            // 2. Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // 3. Insert Transaction header
            $this->txModel->insert([
                'tenant_id'      => $tenantId,
                'branch_id'      => $input['branch_id'],
                'pos_session_id' => $input['pos_session_id'],
                'customer_id'    => $input['customer_id'] ?? null,
                'invoice_number' => $invoiceNumber,
                'subtotal'       => round($subtotal, 2),
                'discount_amount'=> round($discountTotal, 2),
                'tax_amount'     => round($taxTotal, 2),
                'total'          => round($grandTotal, 2),
                'payment_method' => $input['payment_method'],
                'payment_status' => 'Paid',
            ]);

            $txId = $this->txModel->getInsertID();

            // 4. Insert Transaction line items and deduct inventory
            $stockModel = new InventoryStockModel();
            foreach ($items as $item) {
                $this->itemModel->insert([
                    'tenant_id'      => $tenantId,
                    'transaction_id' => $txId,
                    'product_id'     => $item['product_id'],
                    'quantity'       => $item['quantity'],
                    'unit_price'     => $item['unit_price'],
                    'discount_amount'=> $item['discount_amount'] ?? 0,
                    'tax_amount'     => $item['tax_amount'],
                    'total'          => $item['total'],
                ]);

                // Deduct inventory from branch stock
                $stock = $stockModel
                    ->where('tenant_id', $tenantId)
                    ->where('branch_id', $input['branch_id'])
                    ->where('product_id', $item['product_id'])
                    ->first();

                if ($stock) {
                    $stockModel->update($stock['id'], [
                        'quantity' => max(0, $stock['quantity'] - $item['quantity']),
                    ]);
                }
            }

            $db->transComplete();

            if (!$db->transStatus()) {
                throw new \RuntimeException('Transaction could not be completed — DB rollback triggered.');
            }

            return [
                'transaction_id' => $txId,
                'invoice_number' => $invoiceNumber,
                'subtotal'       => round($subtotal, 2),
                'discount_total' => round($discountTotal, 2),
                'tax_total'      => round($taxTotal, 2),
                'grand_total'    => round($grandTotal, 2),
                'payment_status' => 'Paid',
                'created_at'     => date('c'),
            ];

        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }
}
