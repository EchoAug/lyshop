<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 21:52
 */

namespace app\admin\model;


use think\Model;

class BrigeProduct extends Model
{
    public function getStock($brigeID, $productID)
    {
        $result = $this->field(['stock', 'init_stock'])
            ->where('brige_id=' . $brigeID . ' AND product_id=' . $productID)
            ->find();
        return $result;
    }

    public static function getProductsOfBrige($brigeID)
    {
        $productIDS = self::where('brige_id', $brigeID)->column('product_id');
        $productIDS = implode(',', $productIDS);
        $productModel = new Product();
        $products = $productModel->field(['name', 'product_id'])->where('product_id', 'in', $productIDS)->select();
        return $products;
    }

    public static function getAllByBID()
    {
        $result = self::order('brige_id')->select();
        return $result;
    }

    public static function getDataByBP($bid, $pdid)
    {
        $result = self::where(['brige_id' => $bid, 'product_id' => $pdid])->select();
        return $result;
    }

    public static function getItemsOfBID($bid)
    {
        $result = self::where('brige_id',$bid)->select();
        return $result;
    }
}