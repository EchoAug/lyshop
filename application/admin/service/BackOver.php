<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/24
 * Time: 15:25
 */

namespace app\admin\service;


use app\admin\model\BrigeProduct;
use app\admin\model\Product;
use app\admin\model\Supplyment;
use think\Exception;
use think\Model;
use app\admin\model\BackOver as BackOverModel;

class BackOver extends Model
{
    private $back_sum = 0;
    private $cost_sum = 0;

    public function finishBackOver($bid)
    {

        //将冰箱中库存修改
        $supplyment = new Supplyment();
        $supplymentLists = $supplyment->getDataByBID($bid);
        try {
            $brigeProduct = new BrigeProduct();
            $brigeProduct->startTrans();
            foreach ($supplymentLists as $val) {
                $brigeProduct->where(['brige_id' => $bid, 'product_id' => $val->product_id])->setInc('stock', $val->units);
            }
            $brigeProduct->commit();
        } catch (\Exception $e) {
            $brigeProduct->rollback();
        }

        //生成补货单
        $backOverTemp = self::buildBackOrder($bid);
        $backOverData = json_encode($backOverTemp);
        try {
            $backover = new BackOverModel();
            $backover->startTrans();
            $backover->bid = $bid;
            $backover->back_sum = $this->back_sum;
            $backover->cost_sum = $this->cost_sum;
            $backover->back_products = $backOverData;
            $backover->save();
            $backover->commit();
        } catch (\Exception $e) {
            $brigeProduct->rollback();
            $backover->rollback();
        }

        try {
            //完成对表Supplyment的状态修改
            $supModel = new Supplyment();
            $supModel->startTrans();
            //解决不支持事务
            foreach ($supplymentLists as $value) {
                $supModel->update(['status' => 1], ['id' => $value->id]);
            }
            $supplyment->commit();
            return true;
        } catch (\Exception $e) {
            $brigeProduct->rollback();
            $backover->rollback();
            $supplyment->rollback();
            return false;
        }


    }

    private function buildBackOrder($bid)
    {
        static $backItem = [];
        $supplyments = Supplyment::getDataByBID($bid);
        $supplyments->toArray();
        foreach ($supplyments as $value) {
            $this->back_sum += $value['price'] * $value['units'];
            $this->cost_sum += $value['cost_price'] * $value['units'];
            $tempArr['name'] = $value['product_name'];
            $tempArr['product_id'] = $value['product_id'];
            $tempArr['units'] = $value['fact_units'];
            $backItem[] = $tempArr;
            $tempArr = null;
        }
        return $backItem;
    }

    /**
     * 1.获取冰箱id，查找冰箱货品
     * 2.生成冰箱补货列表，商品名称，商品id，补货个数...
     * 3.写入补货表
     */
    public function buildSupplyment($bid)
    {
        $isHave = Supplyment::getBackByBID($bid);
        if ($isHave) {
            return '已经生成过补货单,请去处理~';
        }
        $productOfBrige = BrigeProduct::getItemsOfBID($bid);
        if ($productOfBrige->isEmpty()) {
            return '没有商品,请先添加商品~';
        }
        $isEnough = $this->checkISEnough($productOfBrige);
        if ($isEnough) {
            return '商品充足哦~';
        }
        $result = $this->addProductsToSupplyment($productOfBrige);
        return $result;
    }

    private function addProductsToSupplyment($productOfBrige)
    {
        try {
            foreach ($productOfBrige as $value) {
                $comparation = $value->init_stock - $value->stock;
                if ($comparation > 0) {
                    $product = Product::get($value->product_id);
                    $product->toArray();
                    $supplyment = new Supplyment();
                    $supplyment->bid = $value->brige_id;
                    $supplyment->product_id = $value->product_id;
                    $supplyment->product_name = $product['name'];
                    $supplyment->product_img = $product['first_img'];
                    $supplyment->units = $comparation;
                    $supplyment->fact_units = $comparation;
                    $supplyment->price = $product['price'];
                    $supplyment->cost_price = $product['cost_price'];
                    $supplyment->status = 0;
                    $supplyment->save();
                }
            }
            return 1;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $productOfBrige
     * @return bool
     * 返回true说明库存充足，返回false说明库存不是充足的
     * 返回true说明库存
     */
    private function checkISEnough($productOfBrige)
    {
        foreach ($productOfBrige as $value) {
            if ($value->init_stock != $value->stock) {
                return false;
                break;
            }
            continue;
        }
        return true;
    }

}