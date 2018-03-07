<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/16
 * Time: 14:30
 */

namespace app\admin\model;


use think\Model;

class Recharge extends Model
{
    protected $autoWriteTimestamp = true;

    public function user()
    {
        return $this->hasOne('User','id','uid')
            ->field('id,mobile,openid');
    }
}