<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23
 * Time: 16:05
 */

namespace app\api\service;


use app\api\exception\OrderException;
use app\api\model\Brige;
use app\api\model\BrigeProduct;
use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct;

class Order
{
    protected $uid;
    protected $brige_id;
    protected $oproducts;
    protected $totalprice;

    /**
     * 微信支付购买商品流程
     * @param int $uid 用户id
     * @param array $oProducts 订单商品列表
     * @return array 订单商品状态
     */
    public function place($uid, $brige_id, $oproducts, $total)
    {
        $this->uid = $uid;
        $this->brige_id = $brige_id;
        $this->oproducts = $oproducts;
        $this->totalprice = $total;
        //生成快照
        $snapOrder = $this->snapOrder($oproducts, $brige_id);
        return $snapOrder;
    }

    public function snapOrder($oproducts = [], $brige_id)
    {
        $snap = [];
        $result = $this->checkCount($oproducts, $brige_id);
        if (!$result) {
            throw new OrderException([
                'msg' => '库存量不足，下单失败!'
            ]);
        }
        $result = $this->createOrderByTrans($oproducts);
        return $result;
    }

    public function checkCount($oproducts, $brige_id)
    {
        $brigeProduct = new BrigeProduct();
        foreach ($oproducts as $pro) {
            $result = $brigeProduct->where([
                'product_id' => $pro['product_id'],
                'brige_id' => $brige_id
            ])->find();
            if ($result->stock < $pro['count']) {
                return false;
            } else {
                return true;
            }
        }
    }

    private function createOrderByTrans($snap)
    {
        try {
            $ordersn = makeOrderNo();
            $order = new OrderModel();
            $community = $this->getCommunity();
            $order->community = $community;
            $order->uid = $this->uid;
            $order->ordersn = $ordersn;
            $order->brige_id = $this->brige_id;
            $order->totalprice = $this->totalprice;
            $order->snap_items = json_encode($snap);
            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;
            $snap_items = $order->snap_items;

            $orderProduct = new OrderProduct();
            foreach ($this->oproducts as $p) {
                $orderProduct->product_id = $p['product_id'];
                $orderProduct->product_name = $p['product_name'];
                $orderProduct->imgurl = $p['imgurl'];
                $orderProduct->count = $p['count'];
                $orderProduct->price = $p['price'];
                $orderProduct->order_id = $orderID;
                $orderProduct->brige_id = $this->brige_id;
                $orderProduct->save();
            }
            return [
                'ordersn' => $ordersn,
                'order_id' => $orderID,
                'create_time' => $create_time,
                'snap_items' => $snap_items
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getCommunity()
    {
        $brige = Brige::get($this->brige_id);
        if ($brige->is_community == 1) {
            $community = bcmul($this->totalprice, config('service.floor_point'), 2);
            return $community;
        }
        return 0.00;
    }
}