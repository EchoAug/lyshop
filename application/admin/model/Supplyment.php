<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30
 * Time: 10:54
 */

namespace app\admin\model;


use think\Model;

class Supplyment extends Model
{
    protected $autoWriteTimestamp = true;

    public static function getBackByBID($bid)
    {
        $result = self::where(['bid' => $bid, 'status' => 0])->find();
        return $result;
    }

    public static function getDataByBID($bid)
    {
        $result = self::where(['bid' => $bid, 'status' => 0])->select();
        return $result;
    }
    
}