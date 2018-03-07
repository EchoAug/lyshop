<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/17
 * Time: 10:18
 */

namespace app\api\exception;


class ParamException extends BaseException
{
    public $code = 400;
    public $errorCode = 10000;
    public $msg = "invalid parameters";
}