<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/17
 * Time: 10:31
 */

namespace app\api\validate;


class Company extends BaseValidate
{
    protected $rule = [
        'company_name' => 'require|isNotEmpty',
        'company_scale' => 'require|isNotEmpty',
        'address' => 'require|isNotEmpty',
        'linkman' => 'require|isNotEmpty',
        'phone' => 'require|isMobile',
    ];
}