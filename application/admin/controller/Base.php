<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 15:07
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;
use think\Session;

class Base extends Controller
{
    public function _initialize()
    {
        $this->checkLogin();
        $this->checkScope();
    }

    private function checkLogin()
    {
        $user = Session::get('admin_name');
        if (empty($user)) {
            $this->redirect('Admin/Login/index');
        }
        return true;
    }

    private function checkScope()
    {
        $request = Request::instance();
        $module = strtolower($request->module());
        $controller = strtolower($request->controller());
        $scope = Session::get('scope');
        if ($module == 'admin' && $controller != 'login') {
            if ($scope < 32) {
                $this->error('暂无权限');
            }
        }
        return true;
    }
}