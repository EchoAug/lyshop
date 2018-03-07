<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 15:21
 */

namespace app\admin\controller;


class Upload
{
    public function uploadOne()
    {
        $file = request()->file('file');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $data = [
                    "code" => 200,
                    "msg" => '上传成功',
                    "data" => [
                        "src" => $info->getSaveName()
                    ]
                ];
                echo json_encode($data);
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
}