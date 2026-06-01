<?php

namespace App\Modules\POS\Controllers;

use App\Controllers\BaseController;

class POSController extends BaseController
{
    public function terminal()
    {
        return view('App\Modules\POS\Views\terminal');
    }

    public function sessions()
    {
        $sessionModel = new \App\Modules\POS\Models\POSSessionModel();
        $data['sessions'] = $sessionModel->orderBy('opened_at', 'DESC')->findAll();
        return view('App\Modules\POS\Views\sessions', $data);
    }
}
