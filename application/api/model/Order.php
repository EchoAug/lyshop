<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23
 * Time: 15:40
 */

namespace app\api\model;


use think\Model;

class Order extends Model
{
    protected $autoWriteTimestamp = true;

    public static function getOrderByBU($uid, $bid)
    {
        $result = self::where([
            'uid' => $uid,
            'brige_id' => $bid
        ])
            ->order('create_time desc')
            ->select();
        $result->hidden(['uid','delete_time','snap_img','prepay_id','brige_id','update_time']);
        return $result;
    }
}