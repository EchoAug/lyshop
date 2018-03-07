<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 16:09
 */

namespace app\admin\model;


use think\Model;

class Product extends Model
{
    protected $autoWriteTimestamp = true;

    protected $hidden = ['create_time', 'update_time'];

    public function brige()
    {
        return $this->belongsToMany('Brige', 'brige_product', 'brige_id', 'product_id');
    }

    public function category()
    {
        return $this->belongsTo('Category', 'cid')->field('id,name');
    }

    public function getStockAttr($value)
    {
        $stocks = BrigeProduct::where('product_id', '=', $this->product_id)
                ->sum('sales_sum');
        if(empty($stocks)){
            return 0;
        }
        return $stocks;
    }

}