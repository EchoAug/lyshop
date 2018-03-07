<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 17:28
 */

namespace app\api\Controller;

use app\admin\model\Admin;
use app\api\exception\SuccessMessage;
use app\api\exception\UserException;
use app\api\model\User as UserModel;
use app\api\service\Authorization;
use app\api\validate\Mobile;
use think\Cache;
use think\Exception;
use think\Request;

class User
{
    /**
     * @url 'api/oauth'
     */
    public function getAuthorization()
    {
        $code = input('get.code');
        $wx = new Authorization();
        $result = $wx->getAuth($code);
        $url = "http://www.3ink.cn/shop?uid=%s&is_bind=%s";
        $uri = sprintf($url, $result['uid'], $result['is_bind']);
        header('Location:' . $uri);
        exit;
    }

    /**
     * @url 'api/user_account'
     * @method: get
     * @param uid
     * @return:
     */
    public function getAccount($uid)
    {
        $user = UserModel::get($uid);
        $user->hidden(['delete_time', 'update_time', 'create_time']);
        return $user;
    }

    /**
     * @url: 'api/bind_mobile'
     * @method: post
     * @param uid ,mobile,verify
     */
    public function bindMobile()
    {
        $validate = new Mobile();
        $validate->goCheck();
        $mobile = input('post.mobile');
        $uid = input('post.uid');
        $verify = Cache::get($mobile);
        $code = input('post.verify');
        if ($verify != $code) {
            throw new UserException([
                'msg' => '验证码已过期~'
            ]);
        }
        try {
            $user = UserModel::get($uid);
            $user->mobile = $mobile;
            $user->is_bind = 1;
            $user->save();
            return new SuccessMessage();
        } catch (\Exception $e) {
            throw new UserException([
                'msg' => '用户绑定手机号失败~'
            ]);
        }
    }

    public function getAllUser(Request $request)
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        $conn = '1 ';
        if (!empty($request->param('pageNum'))) {
            $page = $request->param('pageNum');
        } else {
            $page = 1;
        }
        if (!empty($request->param('pageSize'))) {
            $limit = $request->param('pageSize');
        } else {
            $limit = 10;
        }
        if (!empty($request->param('id'))) {
            $conn .= ' AND id=' . $request->param('id');
        }
        if (!empty($request->param('createDate'))) {
            $start = substr($request->param('createDate'), 0, 10);
            $start = strtotime($start);
            $end = substr($request->param('createDate'), -10);
            $end = strtotime($end);
            $conn .= ' AND create_time >= ' . $start . ' AND create_time <= ' . $end;
        }
        $user = UserModel::where($conn)
            ->order('create_time asc')
            ->paginate($limit, false, [
                'page' => $page
            ]);
        $data = [
            'errorNo' => 0,
            'results' => [
                'data' => [
                    'pageNum' => $user->currentPage(),
                    'pageSize' => $user->listRows(),
                    'pages' => $user->total(),
                    'total' => $user->count(),
                    'list' => $user->items()
                ]
            ]
        ];
        return json($data);
    }

    public function getUser(Request $request)
    {
        $user = Admin::get($request->param('id'));
        return $user;
    }
}