<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/20
 * Time: 9:51
 */

namespace app\admin\controller;

use app\admin\model\Brige;
use app\admin\model\Feed as FeedModel;

class Feed extends Base
{
    public function index()
    {
        $conn = '1 ';
        if (input('param.start') && input('param.end')) {
            $start = strtotime(input('param.start'));
            $end = strtotime(input('param.end'));
            $conn .= ' AND create_time >= ' . $start . ' AND create_time <= ' . $end;
        }
        if (input('param.fridge')) {
            $conn .= " AND bid=" . input('param.fridge');
        }
        $fridge = Brige::getBriges();
        $feeds = FeedModel::with(['user', 'brige'])
            ->where($conn)
            ->order('create_time desc')
            ->paginate(25, false, [
                'query' => request()->param()
            ]);
        $this->assign('start', input('param.start'));
        $this->assign('end', input('param.end'));
        $this->assign('fridgeID', input('param.fridge'));
        $this->assign('fridge', $fridge);
        return $this->fetch('', [
            'feeds' => $feeds
        ]);
    }

    public function del()
    {
        if (!request()->isPost()) {
            return 0;
        }
        try {
            FeedModel::destroy(input('post.id'));
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
            FeedModel::destroy($ids);
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }
}