<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 11:24
 */

namespace app\admin\model;


use think\Model;

class Admin extends Model
{
    protected $autoWriteTimestamp = true;

    public function brige()
    {
        return $this->hasOne('Brige','brige_id','brige_id');
    }
}