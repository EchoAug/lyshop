<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23
 * Time: 9:41
 */

namespace app\admin\controller;


use app\admin\model\Order;
use app\admin\model\User as UserModel;
use app\admin\service\Recharge;
use app\api\exception\SuccessMessage;
use think\Controller;
use think\Db;
use think\Request;

class User extends Base
{
    public function index()
    {
        //统计充值人次
        $amount = Db::table('recharge')
            ->field('id,uid,sum(amount) total')
            ->where('status', 'eq', 2)
            ->group('uid')
            ->select();
        //统计充值总金额
        $grossAmount = 0;
        $personTime = count($amount);
        foreach ($amount as $val) {
            $grossAmount = bcadd($grossAmount, round($val['total'], 2), 2);
        }

        //统计后台充值人数
        $amount = Db::table('recharge')
            ->field('id,uid,sum(amount) total')
            ->where('status', 'eq', 3)
            ->group('uid')
            ->select();
        //统计后台充值总金额
        $grossAmountAdmin = 0;
        $personTimeAdmin = count($amount);
        foreach ($amount as $val) {
            $grossAmountAdmin = bcadd($grossAmountAdmin, round($val['total'], 2), 2);
        }

        //统计总的充值情况
        $amount = Db::table('recharge')
            ->field('id,uid,sum(amount) total')
            ->where('status', 'neq', 1)
            ->group('uid')
            ->select();
        //统计后台充值总金额
        $grossAmountAll = 0;
        $personTimeAll = count($amount);
        foreach ($amount as $val) {
            $grossAmountAll = bcadd($grossAmountAll, round($val['total'], 2), 2);
        }

        //页面资源
        $conn = '1 ';
        $data = input('get.');
        if ($data['mobile']) {
            $conn .= ' AND mobile=' . $data['mobile'];
        }
        $users = UserModel::with('consumption')
            ->where($conn)
            ->field(['id', 'openid', 'balance', 'mobile', 'create_time'])
            ->paginate(15, false, [
                'query' => request()->param()
            ]);
        $this->assign('mobile', $data['mobile']);
        return $this->fetch('', [
            'users' => $users,
            'personTime' => $personTime,
            'grossAmount' => $grossAmount,
            'personTimeAdmin' => $personTimeAdmin,
            'grossAmountAdmin' => $grossAmountAdmin,
            'personTimeAll' => $personTimeAll,
            'grossAmountAll' => $grossAmountAll
        ]);
    }

    public function expenseTracker(Request $request)
    {
        $orders = Order::where('uid', '=', $request->param('uid'))
            ->order('id desc')
            ->paginate(10, false);
        return $this->fetch('', [
            'orders' => $orders
        ]);
    }

    public function del()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $id = input('post.id');
        try {
            UserModel::destroy($id);
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
        $data = input('post.ids/a');
        $ids = implode(',', $data);
        try {
            UserModel::destroy($ids);
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }

    /**
     * 后台针对用户余额充值
     */
    public function recharge()
    {
        return $this->fetch();
    }

    public function doRecharge(Request $request)
    {
        if (!request()->isPost()) {
            return 0;
        }
        $rechargeService = new Recharge($request->param('uid'), $request->param('money'));
        $rechargeService->doRecharge();
        return new SuccessMessage([
            'msg' => '充值成功!'
        ]);
    }
}