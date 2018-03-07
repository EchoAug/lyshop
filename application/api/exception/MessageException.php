<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/23
 * Time: 15:36
 */

namespace app\api\exception;


class MessageException extends BaseException
{
    public $code = 400;
    public $msg = '消息发送失败';
    public $errorCode = 70000;
}