<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/18
 * Time: 15:53
 */

namespace app\admin\controller;

use app\admin\model\BackOver;
use app\admin\model\Lost as LostModel;
use app\admin\model\Supplyment;

class Lost extends Base
{
    public function index()
    {
        $lost = LostModel::with(['product', 'brige'])->select();
        return $this->fetch('', [
            'losts' => $lost
        ]);
    }

    public function del($id)
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            LostModel::destroy($id);
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }

    public function batchDel()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $ids = input('post.ids/a');
        $ids = implode(',', $ids);
        try {
            LostModel::destroy($ids);
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }

    //补货记录管理
    public function back()
    {
        $backs = BackOver::with('brige')->paginate(10);
        return $this->fetch('', [
            'backs' => $backs
        ]);
    }

    public function backDetail($id)
    {
        $backover = BackOver::get($id);
        $lists = json_decode($backover->back_products);
        return $this->fetch('',[
            'lists' =>$lists
        ]);
    }

    public function suDel($id)
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            BackOver::destroy($id);
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }

    public function suBatchDel()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $ids = input('post.ids/a');
        $ids = implode(',', $ids);
        try {
            BackOver::destroy($ids);
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }

}