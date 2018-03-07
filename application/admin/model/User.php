<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 13:55
 */

namespace app\admin\model;


use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;

    public function consumption()
    {
        return self::hasMany('Order', 'uid')
            ->where('status', 'neq', 0)
            ->field('uid,totalprice');
    }

    public function getConsumptionAttr($value)
    {
        $consumption = 0;
        foreach ($value as $val) {
            $consumption = bcadd($consumption,$val->totalprice, 2);
        }
        return $consumption;
    }
}