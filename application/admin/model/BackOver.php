<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/24
 * Time: 16:20
 */

namespace app\admin\model;


use think\Model;

class BackOver extends Model
{
    protected $autoWriteTimestamp = true;

    public function brige()
    {
        return $this->hasOne('Brige','brige_id','bid')->field('brige_id,code,address');
    }

    public static function getBackByBID($bid)
    {
        $result = self::where(['bid' => $bid, 'status' => 0])->find();
        return $result;
    }

}