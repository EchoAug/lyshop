<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23
 * Time: 17:23
 */

namespace app\api\service;

use app\api\exception\PayException;
use app\api\exception\SuccessMessage;
use app\api\model\Brige;
use app\api\model\BrigeProduct;
use app\api\model\Order as OrderModel;
use app\api\exception\OrderException;
use app\api\model\Order;
use app\api\model\User;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');


class Pay
{
    private $orderNo;
    private $orderID;
    private $userID;

    function __construct($orderID, $uid)
    {
        if (!$orderID) {
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
        $this->userID = $uid;
    }

    public function pay()
    {
        $order = OrderModel::get($this->orderID);
        $this->orderNo = $order->ordersn;
        return $this->makeWxPreOrder($order->totalprice);
    }

    // 构建微信支付订单信息
    private function makeWxPreOrder($totalPrice)
    {
        $user = User::get($this->userID);
        $openid = $user->openid;

        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('凌云商贩');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('wx.pay_back_url'));

        return $this->getPaySignature($wxOrderData);
    }

    //向微信请求订单号并生成签名
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        // 失败时不会返回result_code
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
//            throw new Exception('获取预支付订单失败');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function recordPreOrder($wxOrder)
    {
        // 必须是update，每次用户取消支付后再次对同一订单支付，prepay_id是不同的
        OrderModel::where('id', '=', $this->orderID)
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    // 签名
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        $rawValues['community'] = $this->getCommunity();
        unset($rawValues['appId']);
        return $rawValues;
    }

    private function getCommunity()
    {
        $order = Order::get($this->orderID);
        return $order->community;
    }

    public function payInBalance()
    {
        $order = Order::get($this->orderID);
        if ($order->status == 2 || $order->status == 3) {
            throw new PayException([
                'msg' => '订单已经支付过，请勿重复支付~'
            ]);
        }
        $user = User::get($this->userID);
        if ($user->balance < $order->totalprice) {
            throw new PayException([
                'msg' => '余额不足，请充值~'
            ]);
        }
        $balance = bcsub($user->balance, $order->totalprice, 2);
        Db::startTrans();
        try {
            $this->changeOrderStatus();
            $this->doEXPR($balance);
            $this->reduceStock($order->snap_items, $order->brige_id);
            Db::commit();
            return [
                'community' => $order->community
            ];
        } catch (Exception $ex) {
            Db::rollback();
            Log::error($ex);
            throw new PayException([
                'msg' => '支付失败'
            ]);
        }
    }

    private function changeOrderStatus()
    {
        Order::where('id', '=', $this->orderID)->update(['status' => 3]);
    }

    private function doEXPR($balance)
    {
        User::where('id', '=', $this->userID)->update(['balance' => $balance]);
    }

    private function reduceStock($items, $brige_id)
    {
        $status = json_decode($items, true);
        $brigeProduct = new BrigeProduct();
        foreach ($status as $v) {
            $brigeProduct->where([
                'product_id' => $v['product_id'],
                'brige_id' => $brige_id
            ])
                ->setInc('sales_sum', $v['count']);
            $brigeProduct->where([
                'product_id' => $v['product_id'],
                'brige_id' => $brige_id
            ])
                ->setDec('stock', $v['count']);
        }
    }
}