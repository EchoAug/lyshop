<?php
namespace app\admin\controller;

use app\api\model\Brige;
use think\Controller;
use think\Db;
use app\admin\model\BrigeProduct;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function welcome()
    {
        $version = Db::query('SELECT VERSION() AS ver');
        $config = [
            'url' => $_SERVER['HTTP_HOST'],
            'document_root' => $_SERVER['DOCUMENT_ROOT'],
            'server_os' => PHP_OS,
            'server_port' => $_SERVER['SERVER_PORT'],
            'server_ip' => $_SERVER['SERVER_ADDR'],
            'server_soft' => $_SERVER['SERVER_SOFTWARE'],
            'php_version' => PHP_VERSION,
            'mysql_version' => $version[0]['ver'],
            'max_upload_size' => ini_get('upload_max_filesize')
        ];
        return $this->fetch('', [
            'config' => $config
        ]);
    }

    /**
     * 折线图数据，近一个月的销售情况
     * @return mixed
     */
    public function totalAmount()
    {
        $start = strtotime("-30 day");
        $end = time();
        $conn = 'create_time >= ' . $start . ' AND create_time < ' . $end. ' AND status = 2 OR status = 3';
        $sql = "SELECT SUM(totalprice) total,FROM_UNIXTIME(create_time, '%Y-%m-%d') AS daytime FROM `order` ".
               " WHERE $conn GROUP BY daytime ORDER BY daytime DESC LiMIT 30";
        $result = Db::query($sql);
		$result = array_reverse($result);
        return $result;
    }
}
