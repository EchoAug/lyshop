<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 15:21
 */

namespace app\admin\controller;

use think\Image;
use think\Loader;

Loader::import('PHPExcel.Classes.PHPExcel');
Loader::import('PHPExcel.Classes.PHPExcel.IOFactory');

class Tool
{
    //图片上传接口（产品图片上传）
    public function uploadOne()
    {
        $file = request()->file('file');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $name = $info->getSaveName();
                $image = Image::open(ROOT_PATH . 'public' . DS . 'uploads' . DS . $name);
                //进行图片压缩
                $image->thumb(100, 100)->save(ROOT_PATH . 'public' . DS . 'uploads' . DS . $name);
                $data = [
                    "code" => 200,
                    "msg" => '上传成功',
                    "data" => [
                        "src" => $name
                    ]
                ];
                echo json_encode($data);
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

    public function uploadAd()
    {
        $file = request()->file('file');
        // 移动到框架应用根目录/public/ad/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'ad');
            if ($info) {
                return $info->getSaveName();
            }
            return false;
        }
        return false;
    }

    /**
     * 导出Excel
     * @param array $data 数据格式
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function exportExcel($data = [])
    {
        header("Content-type: text/html; charset=utf-8");
        $filename = date("Y-m-d", time()) . '补货单';
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("LiBaBa")
            ->setLastModifiedBy("LiBaBa")
            ->setTitle("补货单")
            ->setSubject("凌云售补货单");

//设置标题
        $objPHPExcel->getActiveSheet()->setTitle($filename);

//设置表头
        $key1 = 1;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $key1, '商品名称')
            ->setCellValue('B' . $key1, '单价')
            ->setCellValue('C' . $key1, '进货单价')
            ->setCellValue('D' . $key1, '补货数量');

//设置样式：
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true); //多个单元格
// $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->getColor()->setARGB('FFFF0000'); //设置颜色
// $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true); //单个单元格
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);  //列宽必须单个设置

//写入内容
        foreach ($data as $key => $value) {
            $key1 = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $key1, $value->product_name);


            $objPHPExcel->getActiveSheet()->setCellValue('B' . $key1, $value->price);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $key1, $value->cost_price);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $key1, $value->fact_units);
        }
// $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}