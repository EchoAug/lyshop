<?php
/**
 * Created by PhpStorm.
 * User: August
 * Date: 2017/9/23
 * Time: 17:23
 */

namespace app\api\Controller;

use app\api\service\Pay as PayService;
use app\api\service\WxNotify;

class Pay
{
    /**
     * 微信支付
     * method： GET
     * @return uid,order_id
     * 返回签名，对应接口的数据，拉起支付
     */
    public function getPreOrder()
    {
        $id = input('get.order_id');
        $uid = input('get.uid');
        $pay = new PayService($id, $uid);
        return $pay->pay();
    }

    public function receiveNotify()
    {
        $notify = new WxNotify();
        $notify->Handle();
    }

    /**
     * 余额支付
     * method： GET
     * @param uid,order_id
     * @return '200','ok'
     */
    public function payInBalance()
    {
        $id = input('param.order_id');
        $uid = input('param.uid');
        $pay = new PayService($id, $uid);
        return $pay->payInBalance();
    }
}