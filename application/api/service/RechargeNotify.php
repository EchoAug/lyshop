<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/13
 * Time: 15:06
 */

namespace app\api\service;

use app\api\model\Recharge;
use app\api\model\User;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class RechargeNotify extends \WxPayNotify
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
                $recharge = Recharge::where('ordercode', '=', $orderNo)->lock(true)->find();
				wLog($recharge->id);
                if ($recharge->status == 1) {
                    $this->updateRechargeStatus($recharge->id);
                    $this->updateAmount($recharge->uid, $recharge->amount);
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


    private function updateAmount($uid, $amount)
    {
        switch ($amount) {
            case 300:
                $bonus = 370;
                break;
            case 200:
                $bonus = 245;
                break;
            case 100:
                $bonus = 120;
                break;
            case 50:
                $bonus = 55;
                break;
            default:
				$bb = 0;
                $bonus = bcadd($amount, $bb, 2);
//                return false;
        }
		$user = User::get($uid);
        $balance = bcadd($bonus, $user->balance, 2);
		$this->updateBalance($uid, $balance);
    }

    private function updateRechargeStatus($orderID)
    {
        Recharge::where('id', '=', $orderID)->update(['status' => 2]);
    }
	private function updateBalance($uid, $balance)
	{
		User::where('id', '=', $uid)->update(['balance' => $balance]);
	}
}