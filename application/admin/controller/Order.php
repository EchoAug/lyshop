<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 14:40
 */

namespace app\admin\controller;

use app\admin\model\Brige;
use app\admin\model\Order as OrderModel;
use app\admin\model\Recharge;
use think\Controller;
use think\Db;
use think\Exception;

class Order extends Base
{
    public function index()
    {
        $conn = '1 ';
        $data = input('get.');
        if ($data['start'] && $data['end']) {
            $start = strtotime($data['start']);
            $end = strtotime($data['end']);
            $conn .= ' AND create_time >= ' . $start . ' AND create_time <= ' . $end;
        }
        if ($data['status']) {
            $conn .= ' AND status=' . $data['status'];
        }
        if ($data['brige']) {
            $conn .= " AND brige_id=" . $data['brige'];
        }
        if ($data['ordersn']) {
            $conn .= " AND ordersn='" . $data['ordersn'] . "'";
        }
        $orders = OrderModel::with('user')
            ->where($conn)
            ->order('id desc')
            ->paginate(25, false, [
                'query' => request()->param()
            ]);
        if (!$orders) {
            throw new Exception('暂无商品');
        }

        $briges = Brige::getBriges();
        $this->assign('briges', $briges);
        $this->assign('start', $data['start']);
        $this->assign('end', $data['end']);
        $this->assign('brigeitem', $data['brige']);
        $this->assign('status', $data['status']);
        $this->assign('ordersn', $data['ordersn']);
        $this->assign('orders', $orders);
        return $this->fetch();
    }

    public function del()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            OrderModel::destroy(input('param.id'));
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
        try {
            $data = input('post.ids/a');
            $ids = implode(',', $data);
            OrderModel::destroy($ids);
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }

    public function detail($id)
    {
        $list = OrderModel::get($id)->visible(['snap_items']);
        $data = json_decode($list->snap_items);
        $lists = [];
        foreach ($data as $k => $value) {
            $lists[$k]['imgurl'] = $value->imgurl;
            $lists[$k]['product_name'] = $value->product_name;
            $lists[$k]['price'] = $value->price;
            $lists[$k]['total_price'] = $value->total_price;
            $lists[$k]['count'] = $value->count;
        }
        return $this->fetch('', [
            'lists' => $lists
        ]);
    }

    //用户充值订单
    public function recharge()
    {
        $conn = '1 ';
        $data = input('get.');
        if ($data['start'] && $data['end']) {
            $start = strtotime($data['start']);
            $end = strtotime($data['end']);
            $conn .= ' AND create_time >= ' . $start . ' AND create_time <= ' . $end;
        }
        if ($data['status']) {
            $conn .= ' AND status=' . $data['status'];
        }
        if ($data['keywords']) {
            $conn .= " AND amount='" . $data['keywords'] . "' OR uid=" . $data['keywords'];
        }
        $rechargeOrders = Recharge::with('user')
            ->where($conn)
            ->order('create_time desc')
            ->paginate(25, false, [
                'query' => request()->param()
            ]);
        if (!$rechargeOrders) {
            throw new Exception('暂无商品');
        }
        $this->assign('start', $data['start']);
        $this->assign('end', $data['end']);
        $this->assign('status', $data['status']);
        $this->assign('keywords', $data['keywords']);
        $this->assign('orders', $rechargeOrders);
        return $this->fetch();
    }
}