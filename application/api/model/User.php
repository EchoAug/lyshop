<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23
 * Time: 11:06
 */

namespace app\api\model;


use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;

    public function orders()
    {
        return $this->hasMany('Order','uid','id')
            ->field('snap_items');
    }
}