<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 11:12
 */

namespace app\admin\model;


use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;

    protected $hidden = ['update_time'];
}