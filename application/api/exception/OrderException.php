<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23
 * Time: 16:50
 */

namespace app\api\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单下单失败';
    public $errorCode = 40000;
}