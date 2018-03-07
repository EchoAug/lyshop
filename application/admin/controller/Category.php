<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 10:13
 */

namespace app\admin\controller;


use app\admin\model\Category as CategoryModel;
use think\Controller;
use think\Exception;

class Category extends Base
{
    public function index()
    {
        $categories = CategoryModel::all();
        return $this->fetch('', [
            'categories' => $categories
        ]);
    }
    
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            if ($data) {
                try {
//                    dump($data['field']);die;
                    $category = new CategoryModel($data['field']);
                    $category->save();
                    return 1;
                } catch (\Exception $e) {
                    throw new Exception($e->getMessage());
                    return 0;
                }
            }
        }
        return $this->fetch();
    }

    public function edit($id)
    {
        $category = CategoryModel::get($id);
        return $this->fetch('', [
            'category' => $category
        ]);
    }

    public function updateData()
    {
        $data = input('post.');
        $category = new CategoryModel();
        try {
            $category->update($data['field']);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function del()
    {
        if (!request()->isPost()) {
            return 0;
        }
        $id = input('post.');
        try {
            CategoryModel::destroy($id);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function batchDel()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            $ids = input('post.');
            $list = implode(',',$ids['ids']);
            CategoryModel::destroy($list);
            return 1;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
            return 2;
        }
    }
}