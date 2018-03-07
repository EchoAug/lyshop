<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 16:26
 */

namespace app\admin\controller;


use app\admin\model\Ad;
use app\admin\model\Brige as BrigeModel;
use app\admin\model\BrigeProduct;
use app\admin\model\Lost;
use app\admin\model\Order;
use app\admin\model\Supplyment;
use app\admin\service\BackOver;
use app\admin\service\Brige as BrigeService;
use think\Controller;
use think\Db;
use think\Exception;
use think\Loader;
use think\Request;

Loader::import('PHPqrCode.phpqrcode');

class Brige extends Base
{
    private $id;

    public function index()
    {
        $fridges = BrigeModel::with(['turnoverMoney', 'orderToday', 'productsTotal'])
            ->order('create_time asc')
            ->paginate(10, false);
        $todayAllSum = Order::todayAllTheSum();
        $allTheSum = Order::allTheSum();
        $tempData = BrigeModel::with('productsTotal')->select();
        $residue = 0;
        foreach ($tempData as $value) {
            $residue = bcadd($residue, $value->products_total['total']);
        }
        $this->assign('residue', $residue);
        $this->assign('allTheSum', $allTheSum);
        $this->assign('fridges', $fridges);
        $this->assign('allsum', $todayAllSum->allSum);
        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            try {
                $brige = new BrigeModel($data['field']);
                $brige->save();
                return 1;
            } catch (\Exception $e) {
//                throw new Exception($e->getMessage());
                return 0;
            }
        }
        return $this->fetch();
    }

    public function edit()
    {
        $brige_id = input('param.brige_id');
//        $brige = BrigeModel::get(['brige_id' => $brige_id]);
        $brige = Db::table('brige')->where('brige_id', $brige_id)->find();
        return $this->fetch('', [
            'brige' => $brige
        ]);
    }

    public function updateEdit()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            $data = input('post.field/a');
            $brige = new BrigeModel();
            $brige->save([
                'code' => $data['code'],
                'address' => $data['address'],
                'description' => $data['description']
            ], ['brige_id' => $data['brige_id']]);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function product()
    {
        $id = input('param.id');
        $products = BrigeModel::getProduct($id);
        $products = $products->toArray();
        return $this->fetch('', [
            'id' => $id,
            'products' => $products['product']
        ]);

    }

    public function productAdd()
    {
        $brigeID = input('param.id');
        $productIDS = Db::table('brige_product')->where('brige_id', $brigeID)->column('product_id');
        $productIDS = implode(',', $productIDS);
        $product = Db::table('product')->where('product_id', 'not in', $productIDS)->select();
        return $this->fetch('', [
            'product' => $product
        ]);
    }

    public function saveProduct()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $data = input('post.');
        $result = BrigeProduct::get([
            'brige_id' => $data['field']['brige_id'],
            'product_id' => $data['field']['product_id']
        ]);
        if ($result) {
            return 3;
        }
        try {
            $brigeProduct = new BrigeProduct();
            $brigeProduct->brige_id = $data['field']['brige_id'];
            $brigeProduct->product_id = $data['field']['product_id'];
            $brigeProduct->stock = $data['field']['stock'];
            $brigeProduct->init_stock = $data['field']['stock'];
            $brigeProduct->save();
            return 1;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
//            return 0;
        }

    }

    public function productLost()
    {
        $brigeID = input('param.brige_id');
        $products = BrigeProduct::getProductsOfBrige($brigeID);
        return $this->fetch('', [
            'products' => $products
        ]);
    }

    //丢失记录添加
    public function saveToLost()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $data = input('post.field/a');
        try {
            $lost = new Lost();
            $lost->brige_id = $data['brige_id'];
            $lost->product_id = $data['product_id'];
            $lost->units = $data['units'];
            $lost->save();
            return 1;
        } catch (\Exception $e) {
//            throw new Exception($e->getMessage());
            return 0;
        }
    }

    public function productEdit()
    {
        $data = input('param.');
        $stock = BrigeProduct::getDataByBP($data['brige_id'], $data['product_id']);
        $list = $stock->toArray();
        return $this->fetch('', [
            'stock' => $list[0]
        ]);
    }

    //修改初始库存（初始库存是用来参考的）
    public function updateData()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $data = input('param.field/a');
        try {
            Db::table('brige_product')->where('id', $data['id'])
                ->update([
                    'init_stock' => $data['init_stock']
                ]);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    //修改库存功能
    public function stockEditPage(Request $request)
    {
        $list = BrigeProduct::getDataByBP($request->param('brige_id'), $request->param('product_id'));
        return $this->fetch('', [
            'stock' => $list[0]
        ]);
    }

    public function stockEdit(Request $request)
    {
        try {
            $relation = BrigeProduct::get($request->param('id'));
            $relation->stock = $request->param('stock');
            $relation->save();
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 补货流程，生成补货单流程
     * @return int
     */
    public function backOver()
    {
        $id = input('post.bid');
        $backOver = new BackOver();
        $result = $backOver->buildSupplyment($id);
        if (!$result) {
            return '内部错误!';
        }
        return $result;
    }

    /**
     * 补货模块
     * @return int
     */
    public function backOverPage($bid)
    {
        $supplyments = Supplyment::getDataByBID($bid);
        return $this->fetch('', [
            'bid' => $bid,
            'supplyments' => $supplyments
        ]);
    }

    //补货模块针对补货的删除
    public function supplymentDel($id)
    {
        try {
            $supplyment = new Supplyment();
            $supplyment->destroy($id);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    //补货模块针对补货的修改
    public function supplymentEdit($id)
    {
        $supplyment = Supplyment::get($id);
        return $this->fetch('', [
            'supplyment' => $supplyment
        ]);
    }

    public function updateUnits()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $data = input('post.field/a');
        try {
            $supplyment = new Supplyment();
            $supplyment->save([
                'fact_units' => $data['units']
            ], ['id' => $data['id']]);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * @param $bid
     * @return int|string
     * 补货操作完成，改变补货单状态，即为补货单，同时，将数量加到对应的冰箱库存上
     */
    public function finishBackOver($bid)
    {
        $backover = new BackOver();
        $result = $backover->finishBackOver($bid);
        if (!$result) {
            return '操作失败';
        }
        return 1;
    }

    //导出补货单为excel
    public function exportExcel()
    {
        $bid = input('param.bid');
        $supplymentLists = Supplyment::getDataByBID($bid);
        if (!$supplymentLists) {
            $this->error('暂无补货数据', 'Index/index');
        }
        $tool = new Tool();
        $tool->exportExcel($supplymentLists);
    }

    //删除产品
    public function productDel()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $id = input('post.id');
        try {
            $brigeProduct = new BrigeProduct();
            $brigeProduct->destroy($id);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    //批量删除
    public function productBatchDel()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            $data = input('post.');
            $ids = implode(',', $data['ids']);
            $brigeProduct = new BrigeProduct();
            $brigeProduct->destroy($ids);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    //库存预警模块
    public function floorShow()
    {
        $floorStock = db('brige_product')->where([
            'brige_id' => input('param.brige_id'),
            'product_id' => input('param.product_id')
        ])->find();
        return $this->fetch('', [
            'stock' => $floorStock
        ]);
    }

    public function floorStock()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            Db::table('brige_product')->where('id', '=', input('post.field.id'))
                ->update([
                    'floor_stock' => input('post.field.floor_stock')
                ]);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 统计图表模块
     * @return mixed
     */
    public function sellCharts()
    {
        return $this->fetch();
    }

    public function ajaxSelling()
    {
        $brigeID = input('get.brige_id');
        $products = BrigeModel::getSimpleProducts($brigeID);
        $products = $products->toArray();
        $selling = [];
        if (!$products['product']) {
            return 0;
        }
        $bp = new BrigeProduct();
        foreach ($products['product'] as $key => $val) {
            $selling[$key]['name'] = $val['name'];
            $stock = $bp->getStock($brigeID, $val['product_id']);
            $selling[$key]['stock'] = $stock->stock;
            $rate = divideSaveDouble($stock->stock, $stock->init_stock);
            $selling[$key]['percent'] = $rate * 100;
        }
        $selling = my_sort($selling, 'stock');
        return $selling;
    }

    public function ajaxDaysAmount()
    {
        /**
         * 格式：brige_id,start,end
         * return 0,无数据
         */
        $brige_id = input('get.brige_id');
        $conn = ' 1 ';
        if ($brige_id) {
            $conn .= ' AND brige_id = ' . $brige_id;
        } else {
            return 0;
        }
        if (input('get.start') && input('get.end')) {
            $start = strtotime(input('get.start'));
            $end = strtotime(input('get.end'));
            $conn .= ' AND create_time >= ' . $start . ' AND create_time <= ' . $end;
        }
        $conn = ' AND (status = 2 OR status = 3)';
        //$conn = "status = 2 AND brige_id = 1 AND create_time >= 1507737600 AND create_time <= 1507824000" ;
        $sql = "SELECT SUM(totalprice) total,FROM_UNIXTIME(create_time, '%Y-%m-%d') AS daytime FROM `order` WHERE $conn GROUP BY daytime";
        $result = Db::query($sql);
        return $result;
    }

    public function ajaxDaysAmountInit()
    {
        $brige_id = input('get.brige_id');
//        $brige_id = 1;
        $start = strtotime("-15 day");
        $end = time();
        $conn = ' brige_id = ' . $brige_id . ' AND create_time >= ' . $start . ' AND create_time < ' . $end . ' AND (status = 2 OR status = 3)';
        $sql = "SELECT SUM(totalprice) AS total,FROM_UNIXTIME(create_time, '%Y-%m-%d') AS daytime FROM `order` WHERE $conn GROUP BY daytime";
        $result = Db::query($sql);
        if (!$result) {
            return 0;
        }
        return $result;
    }

    /**
     * 生成冰箱二维码
     */
    public function qrCode()
    {
        $value = config('homeURL') . '?brige_id=' . input('param.bid');
        $errorCorrectionLevel = "L";
        $size = 10;
        $matrixPointSize = "4";
        \QRcode::png($value, false, $errorCorrectionLevel, $size, $matrixPointSize);
        exit;
    }

    //公益冰箱模块
    public function community()
    {
        $brigeService = new BrigeService();
        $briges = $brigeService->dealBrigeCommunityData();
        return $this->fetch('', [
            'briges' => $briges
        ]);
    }

    //创建页面
    public function communityAdd()
    {
        $briges = BrigeModel::getCommonBrige();
        return $this->fetch('', [
            'briges' => $briges
        ]);
    }

    //创建公益冰箱
    public function createCommunity()
    {
        $id = input('post.communityID');
        try {
            $brige = new BrigeModel();
            $brige->save([
                'is_community' => 1
            ], ['brige_id' => $id]);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    //停用公益冰箱
    public function stopCommunity()
    {
        $id = input('post.id');
        try {
            $brige = new BrigeModel();
            $brige->save([
                'is_community' => 0
            ], ['brige_id' => $id]);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    //针对冰箱分配广告
    public function adPage(Request $request)
    {
        $fridge = BrigeModel::get($request->param('bid'));
        $assignedAd = $fridge->ads()->where('position',0)->select();
        $assignedData = [];
        foreach ($assignedAd as $ad) {
            array_push($assignedData, $ad->id);
        }
        $ads = Ad::where('position',0)->select();
        return $this->fetch('', [
            'ads' => $ads,
            'assignedAd' => $assignedData
        ]);
    }

    //放置广告
    public function adGrant(Request $request)
    {
        try {
            $ads = $request->param('ads/a');
            $fridge = BrigeModel::get($request->param('id'));
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
        } catch (\Exception $ex) {
            return 0;
        }

        return 1;
    }

    //支付广告页面
    public function adPayedPage(Request $request)
    {
        $fridge = BrigeModel::get($request->param('bid'));
        $assignedAd = $fridge->ads()->where('position',1)->select();
        $assignedData = [];
        foreach ($assignedAd as $ad) {
            array_push($assignedData, $ad->id);
        }
        $ads = Ad::where('position',1)->select();
        return $this->fetch('',[
            'ads' => $ads,
            'assignedAd' => $assignedData
        ]);
    }

    //设置支付广告逻辑
    public function payedAdGrant(Request $request)
    {
        try {
            $ads = $request->param('ads/a');
            $fridge = BrigeModel::get($request->param('id'));
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
        } catch (\Exception $ex) {
            return 0;
        }

        return 1;
    }
}