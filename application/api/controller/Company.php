<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/17
 * Time: 10:05
 */

namespace app\api\Controller;

use app\api\exception\CompanyException;
use app\api\exception\ParamException;
use app\api\exception\SuccessMessage;
use app\api\validate\Company as CompanyValidate;
use app\api\model\Company as CompanyModel;

/**
 * Class Company
 * @package app\api\Controller
 */
class Company
{
    /**
     * 公司入驻申请
     * @method： POST
     * @param: company_name,company_scale,address,linkman,phone
     * @return: 成功： 201 成功消息
     *          失败： 404   errorCode  msg
     */
    public function settle()
    {
        $validate = new CompanyValidate();
        $validate->goCheck();
        try {
            $company = new CompanyModel();
            $company->company_name = input('post.company_name');
            $company->scale = input('post.company_scale');
            $company->address = input('post.address');
            $company->linkman = input('post.linkman');
            $company->phone = input('post.phone');
            $company->save();
            return new SuccessMessage();
        }catch (\Exception $e){
            throw new CompanyException();
        }
    }
}