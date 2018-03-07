<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/26
 * Time: 14:44
 */

namespace app\admin\service;


use app\admin\model\Recharge as RechargeModel;
use app\api\exception\UserException;
use app\api\model\User;
use think\Db;

class Recharge
{
    //用户充值
    private $uid;
    private $money;

    function __construct($uid, $money)
    {
        $this->uid = $uid;
        $this->money = $money;
    }

    public function doRecharge()
    {
        Db::startTrans();
        try {
            $this->dealUsersAccount();
            $this->createARecharge();
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new UserException([
                'msg' => '充值失败'
            ]);
        }
    }

    private function dealUsersAccount()
    {
        $user = User::get($this->uid);
        $balance = bcadd($user->balance, $this->money, 2);
        $user->balance = $balance;
        $user->save();
    }

    private function createARecharge()
    {
        $orderNo = $this->build_order_no();
        $recharge = new RechargeModel();
        $recharge->uid = $this->uid;
        $recharge->ordercode = $orderNo;
        $recharge->amount = $this->money;
        $recharge->status = 3;
        $recharge->save();
    }

    private function build_order_no()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

}