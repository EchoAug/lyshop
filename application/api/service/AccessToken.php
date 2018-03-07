<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 15:09
 */

namespace app\api\service;


use think\Exception;

class AccessToken
{
    private $tokenURL;

    const TOKEN_CACHE_KEY = 'access';
    const EXPIRE_TIME = 7200;

    function __construct()
    {
        $url = sprintf(config('wx.access_token_uri'),config('wx.app_id'),config('wx.app_secret'));
        $this->tokenURL = $url;
    }
    // 建议用户规模小时每次直接去微信服务器取最新的token
    // 但微信access_token接口获取是有限制的 2000次/天
    public function get()
    {
        $token = $this->getFromCache();
        if(!$token){
            return $this->getFromWxServer();
        }
        else{
            return $token;
        }
    }

    private function getFromCache(){
        $token = cache(self::TOKEN_CACHE_KEY);
        if(!$token){
            return $token;
        }
        return null;
    }

    private function getFromWxServer()
    {
        $token = curl_get($this->tokenURL);
        $token = json_decode($token, true);
        if (!$token)
        {
            throw new Exception('获取AccessToken异常');
        }
        if(!empty($token['errcode'])){
            throw new Exception($token['errmsg']);
        }
        $this->saveToCache($token);
        return $token['access_token'];
    }

    private function saveToCache($token){
        cache(self::TOKEN_CACHE_KEY, $token, self::EXPIRE_TIME);
    }
}