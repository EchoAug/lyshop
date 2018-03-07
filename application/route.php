<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//user
Route::get('api/usercode','api/User/getCode');
Route::get('api/oauth','api/User/getAuthorization');
Route::get('api/user_account','api/User/getAccount');
Route::post('api/bind_mobile','api/User/bindMobile');

//test get Order
Route::post('api/users','api/User/getAllUser');

//product
Route::get('api/products','api/Product/productList');

Route::get('api/cate_products','api/Product/productsSortByCate');

Route::get('api/user_products', 'api/Product/productOfUsers');

//order
Route::post('api/buildOrder','api/Order/placeOrder');
Route::get('api/order_lists','api/Order/getOrderLists');


/**
 * pay
 * 1.微信支付
 * 2.余额支付
 */
Route::get('api/pay/pre_order', 'api/Pay/getPreOrder');
Route::post('api/pay/notify', 'api/Pay/receiveNotify');

Route::get('api/pay/account', 'api/Pay/payInBalance');

//recharge
Route::post('api/recharge', 'api/Recharge/recharging');
Route::post('api/recharge/notify', 'api/Recharge/notify');


//feedback
Route::post('api/feed/feedback','api/Feed/feedBack');

//company
Route::post('api/settle','api/Company/settle');

//message
Route::post('api/get_verify','api/Message/sendVerifyCode');