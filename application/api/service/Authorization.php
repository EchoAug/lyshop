<?php
namespace app\api\service;

use app\api\model\User as userModel;
use app\api\exception\UserException;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14
 * Time: 15:53
 */
class Authorization
{
    //获取access_token,将用户open_id写入数据库，将用户的access_token写入缓存
    public function getAuth($code)
    {
        $accessTokenURI = sprintf(config("wx.access_token_uri"), config("wx.app_id"), config("wx.app_secret"), $code);
        $result = curl_get($accessTokenURI);
        $result = json_decode($result, true);
        if (array_key_exists('errcode', $result) || array_key_exists('errmsg', $result)) {
            throw new Exception('获取access_token异常');
        }
        $openID = $result['openid'];
		$user = new userModel();
		$userdata = $user->where('openid',$openID)->find();
		if($userdata){
			$data = [
				'uid' => $userdata->id,
				'is_bind' => $userdata->is_bind
			];
		}else{
			$user->openid = $openID;
			$user->save();
			$data = [
				'uid' => $user->id,
				'is_bind' => 0
			];
		}
		return $data;
    }
}