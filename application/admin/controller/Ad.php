<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2018/1/29
 * Time: 10:41
 */

namespace app\admin\controller;


use app\admin\model\Brige;
use think\Exception;
use think\Request;
use app\admin\model\Ad as AdModel;

class Ad extends Base
{
    //广告管理首页
    public function index()
    {
        $ads = AdModel::where('position', 0)->select();
        return $this->fetch('', [
            'ads' => $ads
        ]);
    }

    //广告新增上传页
    public function create()
    {
        return $this->fetch();
    }

    //广告上传服务器处理
    public function store(Request $request)
    {
        $tool = new Tool();
        $result = $tool->uploadAd();
        if (!$request) {
            return response('上传失败', 400);
        }
        try {
            $ad = new AdModel();
            $ad->title = $request->param('name');
            $ad->adurl = $result;
            $ad->save();
        } catch (\Exception $e) {
            return response('上传失败', 400);
        }
        return response('成功', 200);
    }

    //查看信息
    public function read(Request $request)
    {
        $ad = AdModel::get($request->param('id'));
        return $this->fetch('', [
            'ad' => $ad
        ]);
    }

    //修改信息页面
    public function edit(Request $request)
    {
        $ad = AdModel::get($request->param('id'));
        return $this->fetch('', [
            'ad' => $ad
        ]);
    }

    //修改详情处理逻辑
    public function update(Request $request)
    {
        try {
            $ad = AdModel::get($request->param('id'));
            $ad->title = $request->param('title');
            $ad->description = $request->param('description');
            $ad->save();
        } catch (\Exception $e) {
            return 0;
        }
        return 1;
    }

    //删除Ad
    public function del($id)
    {
        try {
            AdModel::destroy($id);
        } catch (\Exception $e) {
            return 0;
        }
        return 1;
    }

    //批量配置广告作为默认方案
    public function allotAd($ads)
    {
        $fridges = Brige::all();
        return $this->fetch('', [
            'fridges' => $fridges,
            'ads' => $ads
        ]);
    }

    //批量为广告设置默认方案逻辑操作
    public function allotAdToFridge(Request $request)
    {
        $fridges = $request->param('fridges/a');
        $ads = $request->param('ads/a');
        try {
            foreach ($fridges as $fridge) {
                $result = $this->dealAllot($fridge, $ads);
                if (!$result) {
                    throw new Exception();
                }
            }
        } catch (\Exception $ex) {
            return 0;
        }
        return 1;
    }

    //批量分配广告
    private function dealAllot($id, $ads = [])
    {
        try {
            $fridge = Brige::get($id);
            $ownAds = $fridge->ads()->where('position',0)->select()->column('id');
            //筛选出需要插入的数据
            $addAds = array_diff($ads, $ownAds);
            foreach ($addAds as $ad) {
                $fridge->assignAd($ad);
            }

            //筛选出需要删除的数据
            $delAds = array_diff($ownAds, $ads);
            foreach ($delAds as $ad) {
                $fridge->deleteAd($ad);
            }
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    //批量分配支付广告
    private function dealPayAllot($id, $ads = [])
    {
        try {
            $fridge = Brige::get($id);
            $ownAds = $fridge->ads()->where('position',1)->select()->column('id');
            //筛选出需要插入的数据
            $addAds = array_diff($ads, $ownAds);
            foreach ($addAds as $ad) {
                $fridge->assignAd($ad);
            }

            //筛选出需要删除的数据
            $delAds = array_diff($ownAds, $ads);
            foreach ($delAds as $ad) {
                $fridge->deleteAd($ad);
            }
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * 支付完成显示广告管理小模块
     */
    public function payed()
    {
        $ads = AdModel::where('position', 1)->select();
        return $this->fetch('', [
            'ads' => $ads
        ]);
    }

    //支付完成图片上传页面
    public function createPay()
    {
        return $this->fetch();
    }

    //图片上传
    public function storePayImg(Request $request)
    {
        $tool = new Tool();
        $result = $tool->uploadAd();
        if (!$request) {
            return response('上传失败', 400);
        }
        try {
            $ad = new AdModel();
            $ad->title = $request->param('name');
            $ad->adurl = $result;
            $ad->position = 1;
            $ad->save();
        } catch (\Exception $e) {
            return response('上传失败', 400);
        }
        return response('成功', 200);
    }

    //为冰箱分配支付页广告
    public function allotPayedAd($ads)
    {
        $fridges = Brige::all();
        return $this->fetch('', [
            'fridges' => $fridges,
            'ads' => $ads
        ]);
    }

    //针对冰箱分配支付广告，关联
    public function allotPayAdToFridge(Request $request)
    {
        $fridges = $request->param('fridges/a');
        $ads = $request->param('ads/a');
        try {
            foreach ($fridges as $fridge) {
                $result = $this->dealPayAllot($fridge, $ads);
                if (!$result) {
                    throw new Exception();
                }
            }
        } catch (\Exception $ex) {
            return 0;
        }
        return 1;
    }

}