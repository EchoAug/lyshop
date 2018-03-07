<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/17
 * Time: 15:18
 */

namespace app\admin\controller;

use app\admin\model\Company as CompanyModel;
use think\Exception;

class Company extends Base
{
    public function index()
    {
        $conn = '1 ';
        $data = input('get.');
        if ($data['start'] && $data['end']) {
            $start = strtotime($data['start']);
            $end = strtotime($data['end']);
            $conn .= ' AND create_time >= ' . $start . ' AND create_time <= ' . $end;
        }
        if ($data['status']) {
            $conn .= ' AND status=' . $data['status'];
        }
        if ($data['company_name']) {
            $conn .= " AND company_name like '%" . $data['company_name'] . "%'";
        }
        $companys = CompanyModel::where($conn)->order('create_time desc')->paginate(10, false, [
            'query' => request()->param()
        ]);
        if (!$companys) {
            throw new Exception('暂无商品');
        }

        $this->assign('start', $data['start']);
        $this->assign('end', $data['end']);
        $this->assign('status', $data['status']);
        $this->assign('company_name', $data['company_name']);
        $this->assign('companys', $companys);
        return $this->fetch();
    }

    public function del()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            CompanyModel::destroy(input('param.id'));
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }

    public function batchDel()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            $data = input('post.ids/a');
            $ids = implode(',', $data);
            CompanyModel::destroy($ids);
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }

    public function settle()
    {
        try {
            $id = input('post.id');
            $status = input('post.status');
            $company =CompanyModel::get($id);
            $company->status = $status;
            $company->save();
            return 1;
        }catch (\Exception $e){
            return 0;
        }
    }
}