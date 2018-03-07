<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/24
 * Time: 8:16
 */

namespace app\api\Controller;

use app\api\model\Brige;
use app\api\model\BrigeProduct;
use app\api\model\Category;
use app\api\model\User;

class Product
{
    /**
     * 获取商品接口
     * @url 'api/products'
     * @param $brige_id
     * @return array
     */
    public function productList($brige_id)
    {
        $brige = new Brige();
        $result = $brige->getProducts($brige_id);
        $products = $result->product->visible([
            'product_id', 'name', 'price', 'first_img', 'keyword', 'category.id', 'category.name', 'pivot.stock'
        ]);
        $data = $products->toArray();
        $returnData = [];
        foreach ($data as $key => $product) {
            if ($product['pivot']['stock'] > 0) {
                array_push($returnData, $product);
            }
        }
        $finalResult = [
            "product" => $returnData,
            "address" => $result->address
        ];

        return $finalResult;
    }

    /**
     * 根据类别获取商品接口
     * @param $brige_id ,$token(需要再添加一个token发送)
     * @return array
     */
    public function productsSortByCate($brige_id, $uid)
    {
        //广告
        $fridge = Brige::get($brige_id);
        $ads = $fridge->ads;
        $cateslis = Category::all()->visible(['name', 'id']);
        $cates = $cateslis->toArray();
        $brige = new Brige();
        $result = $brige->getProducts($brige_id);
        $products = $result->product->visible([
            'product_id', 'name', 'price', 'first_img', 'keyword', 'category.name', 'pivot.stock'
        ]);
        foreach ($cates as $k => $cate) {
            foreach ($products as $product) {
                if ($product['category']->name == $cate['name']) {
                    $cates[$k]['children'][] = $product;
                }
            }
        }

        foreach ($cates as $key => $value) {
            if (!array_key_exists('children', $value)) {
                unset($cates[$key]);
            }
        }

        $productsData = $this->productOfUsers($brige_id, $uid);
        array_unshift($cates, $productsData);

        $finalResult = [
            "ads" => $ads,
            "product" => $cates,
            "address" => $result->address
        ];
        return $finalResult;
    }

    //获取用户经常购买的零食,param: token
    public function productOfUsers($fid,$uid)
    {
        //用戶ID
        $user = User::get($uid);
        $products = $user->orders()->where('status', 'neq', 1)->select();
        $productTemp = empty($products) ? $products = [] : $products->toArray();
        $finalPro = $this->buildSortedData($productTemp);
        $productIDs = $this->buildQueryPID($finalPro);
        $productData = \app\api\model\Product::where('product_id', 'in', $productIDs)
            ->select();
		$productData = $productData->toArray();
        $pros = [];
        foreach ($productData as $k => $pro){
            $stock = BrigeProduct::field('stock')
                ->where('brige_id', $fid)
                ->where('product_id',$pro['product_id'])
                ->find();
            $stock = empty($stock) ? ['stock' => 0] : $stock->toArray() ;
            $pros[$k] = $pro;
            $pros[$k]['pivot'] = $stock;
        }
        return [
			'id' => 0,
            'name' => '经常购买',
            'children' => $pros
        ];
    }

    private function buildSortedData(&$products = [])
    {
        static $productData = [];
        foreach ($products as $product) {
            $productArray = json_decode($product['snap_items']);
            $productData = array_merge($productData, $productArray);
        }
        $productDetail = [];
        foreach ($productData as $value) {
            $productDetail[] = object_to_array($value);
        }
        $finalPro = [];
        foreach ($productDetail as $val) {
            if (empty($finalPro[$val['product_id']])) {
                $finalPro[$val['product_id']] = 0;
            }
            $finalPro[$val['product_id']] += intval($val['count']);
        }
        arsort($finalPro);
        $finalPro = array_slice($finalPro, 0, 3, true);
        return $finalPro;
    }

    private function buildQueryPID(&$product = [])
    {
        $productIDs = '';
        if (empty($product)) {
            return $productIDs;
        }
        foreach ($product as $k => $v) {
            $productIDs .= "$k,";
        }
        return rtrim($productIDs, ',');
    }
}