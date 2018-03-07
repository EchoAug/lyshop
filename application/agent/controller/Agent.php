<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/8
 * Time: 16:58
 */

namespace app\agent\controller;


use app\admin\controller\Base;
use app\admin\model\Admin;
use think\Session;

class Agent extends Base
{
    public function changePswPage()
    {
        return $this->fetch();
    }

    public function changePsw()
    {
        try {
            $password = input('post.password');
            $adminID = Session::get('admin_id');
            $admin = Admin::get($adminID);
            $admin->password = md5(md5($password));
            $admin->save();
            return 1;
        } catch (\Exception $e) {
            return 0;
        }

    }
}