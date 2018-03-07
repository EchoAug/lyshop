<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/23
 * Time: 15:11
 */

namespace app\api\service;

use app\api\exception\MessageException;
use app\api\exception\SuccessMessage;
use think\Cache;
use think\Loader;
use app\api\model\Message as MessageModel;

Loader::import('SMS.autoload');

class Message
{
    public function sendToUser($mobile)
    {
        $randNum = rand(1000, 9999);
        $smsOperator = new \SmsOperator();
        //开发者亦可在构造函数中填入配置项$smsOperator = new SmsOperator($cust_code, $cust_pwd, $sp_code, $need_report, $uid);
        $content = '【凌云科技】您的验证码为：' . $randNum . '。如非本人操作，请忽略。';
        try {
            $message = new MessageModel();
            $message->mobile = $mobile;
            $message->content = $content;
            $message->code = $randNum;
            $message->save();
        } catch (\Exception $e) {
            throw new MessageException([
                'msg' => '服务器内部错误~'
            ]);
        }

        // 发送验证码
        $data['destMobiles'] = $mobile;
        $data['content'] = $content;
        $result = $smsOperator->send_comSms($data);
        $res = json_decode(json_encode($result),true);
        if ($res['success'] == false) {
            throw new MessageException([
                'msg' => '获取验证码失败'
            ]);
        }

        //写入缓存
        Cache::set($mobile,$randNum,60);
        $returnData = [
            'code' => 201,
            'verify' => $randNum
        ];
        return $returnData;
    }
}