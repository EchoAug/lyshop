<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 14:22
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\Admin;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        if (request()->isPost()) {
            $data = input('post.data/a');
            $admin = Admin::get(['name' => $data['name']]);
            if (!$admin) {
                $msg = '不存在此用户!';
                return $msg;
            }
            if ($admin->password == md5(md5($data['password']))) {
                session('admin_name', $admin->name);
                session('admin_id', $admin->id);
                session('scope', $admin->scope);
                return $admin->scope;
            } else {
                $msg = '用户名或密码输入错误!';
                return $msg;
            }
        }
        $msg = '未知错误!';
        return $msg;
    }

    public function logout()
    {
        session(null);
        $this->redirect('Admin/Login/index');
    }
}