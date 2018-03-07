<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/13
 * Time: 16:49
 */

namespace app\api\exception;


class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = '成功';
    public $errorCode = '';
}