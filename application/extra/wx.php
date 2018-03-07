<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14
 * Time: 16:13
 */
return [
    //appid
    'app_id' => 'wx3703bd27d5d4d848',

    //app_secret
    'app_secret' => '89ff19fcca07fd6bd3a93948e6be6abf',


    //微信授权模块
    //1、微信授权页面
    'snapi_uri' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=%s#wechat_redirect",

    'snapi_base' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%s#wechat_redirect",
    //2、微信授权跳转页面
    'redirect_uri' => 'http://www.3ink.cn/lyshopV2.0/public/api/usercode',

    //2、使用code换取access_token链接
    'access_token_uri' => "https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code",

    //4、用户信息查询uri
    'user_info_uri' => 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN',

    //微信获取access_token的uri
    'access_token_url' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',

    //支付成功回调地址
    'pay_back_url' => "http://www.3ink.cn/lyshopV2.2/public/api/pay/notify",

    'recharge_back' => "http://www.3ink.cn/lyshopV2.2/public/api/recharge/notify"
	
];