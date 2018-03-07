<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/20
 * Time: 9:52
 */

namespace app\admin\model;


use think\Model;

class Feed extends Model
{
    protected $autoWriteTimestamp = true;

    public function user()
    {
        return $this->hasOne('User', 'id', 'uid')
            ->field('id,mobile,openid');
    }

    public function brige()
    {
        return $this->hasOne('Brige', 'brige_id', 'bid');
    }
}