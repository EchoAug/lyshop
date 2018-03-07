<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 14:47
 */

namespace app\admin\controller;


use app\admin\model\Category as CateModel;
use app\admin\model\Product as ProductModel;
use think\Controller;
use think\Exception;

class Product extends Base
{
    public function index()
    {

        $products = ProductModel::with(['category'])
            ->order('create_time asc')
            ->paginate(15,false);
        if (!$products) {
            throw new Exception('暂无商品');
        }
        $this->assign('products', $products);
        return $this->fetch();
    }

    public function add()
    {
        $category = CateModel::all();
        if ($category) {
            $category = collection($category)->toArray();
        }
        return $this->fetch('', [
            'category' => $category
        ]);
    }

    public function saveData()
    {
        if (!request()->isPost()) {
            return 0;
        }
        if (input('post.')) {
            try {
                $product = new ProductModel();
                $product->name = input('post.name');
                $product->cid = input('post.cid');
                $product->price = input('post.price');
                $product->first_img = input('post.first_img');
                $product->keyword = input('post.keyword');
                $product->description = input('post.description');
                $product->cost_price = input('post.cost_price');
                $product->save();
                return 1;
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
                return 2;
            }
        }
    }

    public function edit()
    {
        $category = CateModel::all();
        if ($category) {
            $category = collection($category)->toArray();
        }
        $product = ProductModel::get(input('param.product_id'));
        return $this->fetch('', [
            'product' => $product,
            'category' => $category
        ]);
    }

    public function updateData()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            $product = ProductModel::get(input('post.id'));
            $product->name = input('post.name');
            $product->cid = input('post.cid');
            $product->price = input('post.price');
            $product->first_img = input('post.first_img');
            $product->keyword = input('post.keyword');
            $product->description = input('post.description');
            $product->cost_price = input('post.cost_price');
            $product->save();
            return 1;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
            return 2;
        }
    }

    public function del()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            ProductModel::destroy(input('param.id'));
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
            $data = input('param.');
            $ids = implode(',', $data['ids']);
            ProductModel::destroy($ids);
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }
}
