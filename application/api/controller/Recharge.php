<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/13
 * Time: 11:30
 */

namespace app\api\Controller;

use app\api\service\Recharge as RechargeService;
use app\api\service\RechargeNotify;

//充值类
class Recharge
{
    /**
     * @method: post
     * @param: uid(用户id),amount(充值金额),bonus_type(奖励类型，一般为默认1无附加奖励)
     */
    public function recharging()
    {
        $uid = input('post.uid');
        $amount = input('post.amount');
        $recharge = new RechargeService($uid, $amount);
        return $recharge->doRecharge();
    }

    public function notify()
    {
        $rechargeNotify = new RechargeNotify();
        $rechargeNotify->Handle();
    }
}