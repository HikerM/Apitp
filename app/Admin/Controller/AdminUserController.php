<?php

namespace App\Http\Controllers\Admin;
use app\BaseController;
use think\Request;

class AdminUserController extends BaseController
{
    public function store(Request $request)
    {
        $this->verify([
            'account' => '',
            'password' => '',
            'realname' => '',
            'type' => '',
            'company_id' => '',
            'phone' => 'no_required|mobile',
            'avatar' => 'no_required|egnum|can_null',
        ], 'POST');
        \App\Admin\Logic\AdminUserLogic::addAdminUser($this->verifyData);

        return $this->response();
    }
}