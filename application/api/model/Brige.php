<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 16:33
 */

namespace app\api\model;


use think\Model;

class Brige extends Model
{
    protected $autoWriteTimestamp = true;

    protected $hidden = [
        'update_time','create_time','code','status','brige_id','description'
    ];

    //定义多对多关系的关联模型
    public function product()
    {
        return $this->belongsToMany('Product', 'brige_product', 'product_id', 'brige_id')
				->where('pivot.stock','>',0);
    }

    //关联与广告的关联模型
    public function ads()
    {
        return $this->belongsToMany('Ad', 'ad_brige', 'ad_id', 'brige_id');
    }


    public static function getProducts($id)
    {
        $product = self::with(['product','product.category'])->find($id);
        return $product;
    }
	
}