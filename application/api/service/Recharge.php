<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/13
 * Time: 11:53
 */

namespace app\api\service;

use app\api\model\Recharge as RechargeModel;
use app\api\model\User;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Recharge
{
    private $uid;
    private $amount;
    private $orderID;
    private $orderCode;

    public function __construct($uid, $amount)
    {
        $this->uid = $uid;
        $this->amount = $amount;
    }

    /**
     * 1.生成订单号，将订单生成写入数据库
     * 2.根据订单号获取prepay_id
     * 3.拉起支付
     */
    public function doRecharge()
    {
        $this->orderCode = build_order_no();
        $this->buildRechargeOrder();
        return $this->buildWeChatPreOrder();
    }

    private function buildWeChatPreOrder()
    {
        $user = User::get($this->uid);
        $openid = $user->openid;

        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderCode);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($this->amount * 100);
        $wxOrderData->SetBody('凌云商贩');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('wx.recharge_back'));

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
        RechargeModel::where('id', '=', $this->orderID)
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
        unset($rawValues['appId']);
        return $rawValues;
    }

    public function buildRechargeOrder()
    {
        $recharge = new RechargeModel();
        $recharge->uid = $this->uid;
        $recharge->amount = $this->amount;
        $recharge->ordercode = $this->orderCode;
        $recharge->save();
        $this->orderID = $recharge->id;
        return true;
    }
}