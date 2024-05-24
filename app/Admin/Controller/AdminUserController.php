<?php

namespace App\Admin\Controller;
use app\BaseController;
use think\Request;

class AdminUserController extends BaseController
{
    public function index()
    {
        echo 1231213;
    }
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
        ], 'GET');
        \app\Admin\Logic\AdminUserLogic::addAdminUser($this->verifyData);
        return $this->response();
    }
}