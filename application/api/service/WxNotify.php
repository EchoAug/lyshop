<?php

namespace app\api\service;


use app\api\model\BrigeProduct;
use app\api\model\Order;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

//Loader::import('WxPay.WxPay', EXTEND_PATH, '.Data.php');


class WxNotify extends \WxPayNotify
{
//    protected $data = <<<EOD
//<xml><appid><![CDATA[wxaaf1c852597e365b]]></appid>
//<bank_type><![CDATA[CFT]]></bank_type>
//<cash_fee><![CDATA[1]]></cash_fee>
//<fee_type><![CDATA[CNY]]></fee_type>
//<is_subscribe><![CDATA[N]]></is_subscribe>
//<mch_id><![CDATA[1392378802]]></mch_id>
//<nonce_str><![CDATA[k66j676kzd3tqq2sr3023ogeqrg4np9z]]></nonce_str>
//<openid><![CDATA[ojID50G-cjUsFMJ0PjgDXt9iqoOo]]></openid>
//<out_trade_no><![CDATA[A301089188132321]]></out_trade_no>
//<result_code><![CDATA[SUCCESS]]></result_code>
//<return_code><![CDATA[SUCCESS]]></return_code>
//<sign><![CDATA[944E2F9AF80204201177B91CEADD5AEC]]></sign>
//<time_end><![CDATA[20170301030852]]></time_end>
//<total_fee>1</total_fee>
//<trade_type><![CDATA[JSAPI]]></trade_type>
//<transaction_id><![CDATA[4004312001201703011727741547]]></transaction_id>
//</xml>
//EOD;

    public function NotifyProcess($data, &$msg)
    {
        if ($data['result_code'] == 'SUCCESS') {
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try {
                $order = Order::where('ordersn', '=', $orderNo)->lock(true)->find();
                if ($order->status == 1) {
                    $this->updateOrderStatus($order->id);
                    $this->reduceStock($order->snap_items, $order->brige_id);
                }
                Db::commit();

            } catch (Exception $ex) {
                Db::rollback();
                Log::error($ex);
                return false;
            }
        }
        return true;
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

    private function updateOrderStatus($orderID)
    {
        Order::where('id', '=', $orderID)->update(['status' => 2]);
    }
}