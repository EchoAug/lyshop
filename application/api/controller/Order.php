<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23
 * Time: 15:46
 */

namespace app\api\Controller;

use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use think\Exception;

class Order
{
    /**
     * @url 'api/buildOrder'
     * @post ['uid, products [product_id,product_name,imgurl,count,price],brige_id，total']
     * @return ['ordersn','orderid','create_time',snapitems]
     */
    public function placeOrder()
    {
        $uid = input('post.uid');
        $brige_id = input('post.brige_id');
        $products = input('post.products/a');
        $total = input('post.total');
        $order = new OrderService();
        $status = $order->place($uid, $brige_id, $products, $total);
        return $status;
    }

    /**
     * @url 'api/order_lists'
     * @get ['uid','bid']
     * @return []
     */
    public function getOrderLists()
    {
        $uid = input('param.uid');
        $bid = input('param.bid');
        $order_lists = OrderModel::getOrderByBU($uid, $bid);
        if(!$order_lists){
            throw new Exception('暂无订单');
        }
        return $order_lists;
    }
}