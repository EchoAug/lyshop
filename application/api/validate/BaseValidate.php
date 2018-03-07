<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/17
 * Time: 10:14
 */

namespace app\api\validate;


use app\api\exception\ParamException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 检测所有客户端发来的参数是否符合验证类规则
     * 基类定义了很多自定义验证方法
     * 这些自定义验证方法其实，也可以直接调用
     * @throws ParamException
     * @return true
     */
    public function goCheck()
    {
        $request = Request::instance();
        $params = $request->param();
        //将来需要在header验证token在此处添加获取header的token
        if (!$this->batch()->check($params)) {
            $exception = new ParamException([
                'msg' => is_array($this->error) ? implode(';',$this->error) : $this->error,
            ]);
            throw $exception;
        }
        return true;
    }

    protected function isPositiveInteger($value, $rule='', $data='', $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }

    protected function isNotEmpty($value, $rule='', $data='', $field='')
    {
        if (empty($value)) {
            return $field . '不允许为空';
        } else {
            return true;
        }
    }

    /**没有使用TP的正则验证，集中在一处方便以后修改
    *不推荐使用正则，因为复用性太差
    *手机号的验证规则
    */
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}