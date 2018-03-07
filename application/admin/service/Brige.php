<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/10/31
 * Time: 16:10
 */

namespace app\admin\service;


use app\admin\model\Admin;
use app\admin\model\Order;
use think\Model;
use app\admin\model\Brige as BrigeModel;
use think\Session;

class Brige extends Model
{
    public function dealBrigeData()
    {
        $isBackOver = 0;
        $brige = BrigeModel::with(['turnover', 'product'])
                ->order('brige_id asc')
                ->select();
        $brige->visible(['brige_id', 'code', 'description', 'address', 'create_time', 'status', 'turnover.totalprice', 'product']);
        $briges = collection($brige)->toArray();
        foreach ($briges as $key => $br) {
            $turnover = floatval(0);
            $residue = floatval(0);
            $dayliSum = Order::todayPerBrigeSum($br['brige_id']);
            foreach ($br['turnover'] as $turn) {
                $turnover = bcadd(floatval($turnover), floatval($turn['totalprice']), 2);
            }
            foreach ($br['product'] as $value) {
                $rest = $value['pivot']['stock'] * $value['price'];
                $residue = bcadd(floatval($residue), floatval($rest), 2);
                if ($value['pivot']['stock'] <= $value['pivot']['floor_stock']) {
                    $isBackOver = 1;
                }
            }
            $briges[$key]['money'] = $turnover;
            $briges[$key]['residue'] = $residue;
            $briges[$key]['isBackOver'] = $isBackOver;
            $briges[$key]['dayliSum'] = $dayliSum[0]->sumPrice;
            $briges[$key]['turnover'] = null;
            $briges[$key]['product'] = null;
            $isBackOver = 0;
        }
        return $briges;
    }

    public function dealBrigeCommunityData()
    {
        $isBackOver = 0;
        $brige = BrigeModel::with(['turnover', 'product'])->where('is_community', '=', 1)->select();
        $brige->visible(['brige_id', 'code', 'description', 'address', 'create_time', 'status', 'turnover.totalprice', 'product']);
        $briges = collection($brige)->toArray();
        foreach ($briges as $key => $br) {
            $turnover = floatval(0);
            $residue = floatval(0);
            $dayliSum = Order::todayPerBrigeSum($br['brige_id']);
            foreach ($br['turnover'] as $turn) {
                $turnover = bcadd(floatval($turnover), floatval($turn['totalprice']), 2);
            }
            foreach ($br['product'] as $value) {
                $rest = $value['pivot']['stock'] * $value['price'];
                $residue = bcadd(floatval($residue), floatval($rest), 2);
                if ($value['pivot']['stock'] <= $value['pivot']['floor_stock']) {
                    $isBackOver = 1;
                }
            }
            $briges[$key]['money'] = $turnover;
            $briges[$key]['residue'] = $residue;
            $briges[$key]['isBackOver'] = $isBackOver;
            $briges[$key]['dayliSum'] = $dayliSum[0]->sumPrice;
            $briges[$key]['community'] = bcmul($dayliSum[0]->sumPrice, config('service.floor_point'), 2);
            $briges[$key]['turnover'] = null;
            $briges[$key]['product'] = null;
            $isBackOver = 0;
        }
        return $briges;
    }

    public function dealAgentBrigeData()
    {
        $userID = Session::get('admin_id');
        $user = Admin::get($userID);
        $isBackOver = 0;
        $brige = BrigeModel::with(['turnover', 'product'])
            ->select(['brige_id'=>$user->brige_id]);
        $brige->visible(['brige_id', 'code', 'description', 'address', 'create_time', 'status', 'turnover.totalprice', 'product']);
        $briges = collection($brige)->toArray();
        foreach ($briges as $key => $br) {
            $turnover = floatval(0);
            $residue = floatval(0);
            $dayliSum = Order::todayPerBrigeSum($br['brige_id']);
            foreach ($br['turnover'] as $turn) {
                $turnover = bcadd(floatval($turnover), floatval($turn['totalprice']), 2);
            }
            foreach ($br['product'] as $value) {
                $rest = $value['pivot']['stock'] * $value['price'];
                $residue = bcadd(floatval($residue), floatval($rest), 2);
                if ($value['pivot']['stock'] <= $value['pivot']['floor_stock']) {
                    $isBackOver = 1;
                }
            }
            $briges[$key]['money'] = $turnover;
            $briges[$key]['residue'] = $residue;
            $briges[$key]['isBackOver'] = $isBackOver;
            $briges[$key]['dayliSum'] = $dayliSum[0]->sumPrice;
            $briges[$key]['turnover'] = null;
            $briges[$key]['product'] = null;
            $isBackOver = 0;
        }
        return $briges;
    }
}