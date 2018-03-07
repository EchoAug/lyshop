<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2018/2/1
 * Time: 10:59
 */

namespace app\api\model;


use think\Model;

class Ad extends Model
{
    protected $hidden = ['position','create_time','update_time','delete_time','pivot','description'];
}