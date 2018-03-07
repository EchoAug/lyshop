<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 16:33
 */

namespace app\admin\model;


use think\Model;

class Brige extends Model
{
    protected $autoWriteTimestamp = true;

    protected $hidden = [
        'update_time'
    ];

    public function turnover()
    {
        return $this->hasMany('Order', 'brige_id', 'brige_id')
            ->where('status', '=', 2);
    }

    public function turnoverMoney()
    {
        return $this->hasMany('Order', 'brige_id', 'brige_id')
            ->where('status', '=', 2)
            ->field('totalprice,brige_id');
    }

    public function orderToday()
    {
        return $this->hasMany('Order', 'brige_id', 'brige_id')
            ->where('status', '=', 2)
            ->whereTime('create_time', 'today')
            ->field('brige_id,totalprice');
    }

    //定义多对多关系的关联模型
    public function product()
    {
        return $this->belongsToMany('Product', 'brige_product', 'product_id', 'brige_id');
    }

    //关联与广告的关联模型
    public function ads()
    {
        return $this->belongsToMany('Ad', 'ad_brige', 'ad_id', 'brige_id');
    }

    //定义多对多关系的关联模型
    public function productsTotal()
    {
        return $this->belongsToMany('Product', 'brige_product', 'product_id', 'brige_id');
    }

    public function getProductsTotalAttr($value)
    {
        $isBackOver = 0;
        $productsTotal = 0;
        foreach ($value as $val) {
            if ($val->pivot->stock <= $val->pivot->floor_stock) {
                $isBackOver = 1;
            }
            $totalTemp = bcmul($val->price, $val->pivot->stock, 2);
            $productsTotal = bcadd($productsTotal, $totalTemp, 2);
        }
        return [
            'total' => $productsTotal,
            'isBack' => $isBackOver
        ];
    }

    public function getTurnoverMoneyAttr($value)
    {
        $turnover = 0;
        foreach ($value as $val) {
            $turnover = bcadd($turnover, $val->totalprice, 2);
        }
        return $turnover;
    }

    public function getOrderTodayAttr($value)
    {
        $moneyToday = 0;
        foreach ($value as $val) {
            $moneyToday = bcadd($moneyToday, $val->totalprice, 2);
        }
        return $moneyToday;
    }

    public static function getProduct($id)
    {
        $product = self::with(['product'])->find($id);
        return $product;
    }

    public static function getProducts($id)
    {
        $product = self::with(['product', 'product.category'])->find($id);
        return $product;
    }

    public static function getSimpleProducts($id)
    {
        $product = self::with(['product'])->find($id);
        $product->visible(['brige_id', 'code', 'address', 'product.product_id', 'product.name']);
        return $product;
    }

    public static function getBriges()
    {
        $result = self::all()->visible(['brige_id', 'code']);
        return $result;
    }

    public static function getCommonBrige()
    {
        $result = self::where('is_community', '=', 0)
            ->select();
        return $result;
    }

    //配置广告
    public function assignAd($ad)
    {
        return $this->ads()->save($ad);
    }

    //解除广告关联
    public function deleteAd($ad)
    {
        return $this->ads()->detach($ad);
    }

}