<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/13
 * Time: 15:43
 */

namespace app\api\exception;


class PayException extends BaseException
{
    public $code = 404;
    public $msg = '支付未成功';
    public $errorCode = 40000;
}