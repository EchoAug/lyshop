<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/21
 * Time: 10:54
 */

namespace app\api\validate;


class Mobile extends BaseValidate
{
    protected $rule = [
        'mobile' => 'require|isMobile'
    ];
}