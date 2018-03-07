<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/24
 * Time: 8:26
 */

namespace app\api\model;


use think\Model;

class Product extends Model
{
    protected $hidden = ['stock', 'description', 'discount_price', 'create_time', 'update_time'];

    public function brige()
    {
        return $this->belongsToMany('Brige', 'brige_product', 'brige_id', 'product_id');
    }

    public function category()
    {
        return $this->belongsTo('Category', 'cid');
    }
    
}