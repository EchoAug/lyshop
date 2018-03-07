<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14
 * Time: 15:04
 */

namespace app\api\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户授权未成功';
    public $errorCode = 20000;
}