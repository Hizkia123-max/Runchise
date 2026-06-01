<?php

namespace App\Modules\POS\Controllers\API;

use App\Controllers\BaseController;
use App\Modules\POS\Services\CheckoutService;

class POSApiController extends BaseController
{
    protected CheckoutService $checkoutService;

    public function __construct()
    {
        $this->checkoutService = new CheckoutService();
    }

    public function createTransaction()
    {
        $input = $this->request->getJSON(true);

        $rules = [
            'branch_id'      => 'required|integer',
            'pos_session_id' => 'required|integer',
            'payment_method' => 'required|in_list[Cash,QRIS,Card,Split]',
            'items'          => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success'    => false,
                'error_code' => 'ERR_VALIDATION_FAILED',
                'message'    => 'Input validation failed.',
                'errors'     => $this->validator->getErrors(),
            ]);
        }

        try {
            $result = $this->checkoutService->process($input);
            return $this->response->setStatusCode(201)->setJSON([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data'    => $result,
                'meta'    => ['timestamp' => date('c')],
            ]);
        } catch (\Exception $e) {
            log_message('error', 'POS Transaction error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success'    => false,
                'error_code' => 'ERR_INTERNAL_SERVER_ERROR',
                'message'    => $e->getMessage(),
            ]);
        }
    }

    public function listTransactions()
    {
        $txModel  = new \App\Modules\POS\Models\TransactionModel();
        $branchId = $this->request->getGet('branch_id');
        $limit    = (int) ($this->request->getGet('limit') ?? 20);

        $builder = $txModel->orderBy('created_at', 'DESC')->limit($limit);
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        $transactions = $builder->findAll();

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'Transactions retrieved',
            'data'    => $transactions,
            'meta'    => ['timestamp' => date('c'), 'count' => count($transactions)],
        ]);
    }

    public function getTransaction($id)
    {
        $txModel     = new \App\Modules\POS\Models\TransactionModel();
        $itemModel   = new \App\Modules\POS\Models\TransactionItemModel();
        $transaction = $txModel->find($id);

        if (!$transaction) {
            return $this->response->setStatusCode(404)->setJSON([
                'success'    => false,
                'error_code' => 'ERR_RESOURCE_NOT_FOUND',
                'message'    => 'Transaction not found.',
            ]);
        }

        $transaction['items'] = $itemModel->where('transaction_id', $id)->findAll();

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'data'    => $transaction,
            'meta'    => ['timestamp' => date('c')],
        ]);
    }

    public function openSession()
    {
        $input = $this->request->getJSON(true);
        $sessionModel = new \App\Modules\POS\Models\POSSessionModel();

        $sessionModel->insert([
            'tenant_id'    => service('tenant')->getId(),
            'branch_id'    => $input['branch_id'],
            'user_id'      => session()->get('user_id'),
            'opening_cash' => $input['opening_cash'] ?? 0,
            'status'       => 'Open',
            'opened_at'    => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setStatusCode(201)->setJSON([
            'success'    => true,
            'message'    => 'POS session opened successfully',
            'session_id' => $sessionModel->getInsertID(),
            'meta'       => ['timestamp' => date('c')],
        ]);
    }

    public function closeSession()
    {
        $input      = $this->request->getJSON(true);
        $sessionModel = new \App\Modules\POS\Models\POSSessionModel();

        $sessionModel->update($input['session_id'], [
            'closing_cash' => $input['closing_cash'] ?? 0,
            'status'       => 'Closed',
            'closed_at'    => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'POS session closed successfully',
            'meta'    => ['timestamp' => date('c')],
        ]);
    }
}
