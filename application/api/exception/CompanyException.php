<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/17
 * Time: 11:42
 */

namespace app\api\exception;


class CompanyException extends BaseException
{
    public $code = 400;
    public $msg = '用户入驻失败';
    public $errorCode = 60000;
}