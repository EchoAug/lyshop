<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 15:02
 */

namespace app\admin\model;


use think\Model;

class Order extends Model
{
    public function user()
    {
        return $this->hasOne('User','id','uid')
            ->field('id,openid,mobile');
    }

    public function brigeCode()
    {
        return $this->hasOne('Brige', 'brige_id', 'bid')->field('brige_id,code');
    }

    public static function todayPerBrigeSum($bid)
    {
        $result = self::where('brige_id', '=', $bid)
            ->whereTime('create_time', 'today')
            ->where(' (status=2 OR status = 3)')
            ->field('sum(totalprice) sumPrice')
            ->select();
        return $result;
    }

    public static function todayAllTheSum()
    {
        $result = self::whereTime('create_time', 'today')
            ->where(' (status=2 OR status = 3) ')
            ->field('sum(totalprice) allSum')
            ->select();
        return $result[0];
    }

    public static function allTheSum()
    {
        $result = self::where('status', 'neq', 1)
            ->sum('totalprice');
        return $result;
    }
}