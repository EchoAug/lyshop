<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/18
 * Time: 15:34
 */

namespace app\admin\model;


use think\Model;

class Lost extends Model
{
    protected $autoWriteTimestamp = true;

    public function product()
    {
        return $this->hasOne('Product', 'product_id', 'product_id')->field('product_id,name,price');
    }

    public function brige()
    {
        return $this->hasOne('Brige','brige_id','brige_id')->field('brige_id,code');
    }
}