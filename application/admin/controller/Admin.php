<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 15:28
 */

namespace app\admin\controller;


use app\admin\model\Admin as AdminModel;
use app\admin\model\Brige;
use think\Controller;
use think\Exception;

class Admin extends Base
{
    public function index()
    {
        $admins = AdminModel::where('scope', '=', 32)
            ->select();
        return $this->fetch('', [
            'admins' => $admins
        ]);
    }

    public function add()
    {
        return $this->fetch();
    }

    public function edit()
    {
        if (request()->isGet()) {
            $id = input('param.id');
            $admin = AdminModel::get($id);
        }
        return $this->fetch('', [
            'admin' => $admin
        ]);
    }

    public function del()
    {
        $id = input('post.id');
        try {
            AdminModel::destroy($id);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function saveAdmin()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $data = input('post.field/a');
        if ($data) {
            try {
                $admin = new AdminModel();
                if ($admin->where('name', $data['name'])->find()) {
                    return 3;
                }
                $admin->name = $data['name'];
                $admin->password = md5(md5($data['password']));
                $admin->mobile = $data['mobile'];
                $admin->save();
                return 1;
            } catch (\Exception $e) {
                return 2;
            }
        }
    }

    public function updateData()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $data = input('post.field/a');
        if ($data) {
            try {
                $admin = AdminModel::get($data['id']);
                $admin->name = $data['name'];
                $admin->mobile = $data['mobile'];
                $admin->save();
                return 1;
            } catch (\Exception $e) {
                return 2;
            }
        }
    }

    public function editPassword()
    {
        return $this->fetch();
    }

    public function updatePassword()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $data = input('post.field/a');
        if ($data) {
            try {
                $admin = AdminModel::get($data['id']);
                $admin->password = md5(md5($data['password']));
                $admin->save();
                return 1;
            } catch (\Exception $e) {
                return 2;
            }
        }
    }

    public function agent()
    {
        $agents = AdminModel::where('scope', '=', 16)
            ->with('brige')->select();
        return $this->fetch('', [
            'agents' => $agents
        ]);
    }

    public function agentAddPage()
    {
        $briges = Brige::all()
            ->visible(['brige_id', 'code']);
        return $this->fetch('', [
            'briges' => $briges
        ]);
    }

    public function agentAdd()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $data = input('post.field/a');
        if ($data) {
            try {
                $admin = new AdminModel();
                if ($admin->where('name', $data['name'])->find()) {
                    return 3;
                }
                $admin->name = $data['name'];
                $admin->mobile = trim($data['mobile']);
                $admin->password = md5(md5(config('service.base_password')));
                $admin->scope = 16;
                $admin->brige_id = $data['brige_id'];
                $admin->save();
                return 1;
            } catch (\Exception $e) {
                return 2;
            }
        }
    }

    public function agentEditPage()
    {
        $id = input('param.id');
        $agent = AdminModel::get($id);
        $briges = Brige::all()
            ->visible(['brige_id', 'code']);
        return $this->fetch('', [
            'agent' => $agent,
            'briges' => $briges
        ]);
    }

    public function agentEdit()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $data = input('post.field/a');
        if ($data) {
            try {
                $admin = AdminModel::get($data['id']);
                $admin->name = $data['name'];
                $admin->mobile = trim($data['mobile']);
                $admin->brige_id = $data['brige_id'];
                $admin->save();
                return 1;
            } catch (\Exception $e) {
                return 2;
            }
        }
    }

    public function agentDel()
    {
        $id = input('post.id');
        try {
            AdminModel::destroy($id);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }
}