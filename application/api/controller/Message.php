<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/23
 * Time: 14:41
 */

namespace app\api\Controller;

use app\api\exception\MessageException;
use app\api\exception\SuccessMessage;
use app\api\validate\Mobile;
use app\api\service\Message as MessageService;

class Message
{
    /**
     * @api 'api/get_verify'
     * @param "mobile"
     * @return 成功消息或失败
     */
    public function sendVerifyCode()
    {
        $validate = new Mobile();
        $validate->goCheck();
        $mobile = input('post.mobile');
        $message = new MessageService();
        $result = $message->sendToUser($mobile);
		return $result;
    }
}