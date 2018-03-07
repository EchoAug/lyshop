<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\user\expensetracker.html";i:1514187800;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1518229341;}*/ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo config('title'); ?></title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="__STATIC__/css/font.css">
    <link rel="stylesheet" href="__STATIC__/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="__STATIC__/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="__STATIC__/js/xadmin.js"></script>
    <script type="text/javascript" src="__STATIC__/js/config.js"></script>

</head>
<body>
<div class="x-body">
    <table class="layui-table">
        <thead>
        <tr>
            <!--<th>-->
            <!--<div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">-->
            <!--&#xe605;</i></div>-->
            <!--</th>-->
            <th>编号</th>
            <th>订单号</th>
            <th>总金额</th>
            <th>应付金额</th>
            <th>冰箱ID</th>
            <th>支付状态</th>
            <th>下单时间</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($orders) || $orders instanceof \think\Collection || $orders instanceof \think\Paginator): $i = 0; $__LIST__ = $orders;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <!--<td>-->
            <!--<div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $vo['id']; ?>'><i-->
            <!--class="layui-icon">-->
            <!--&#xe605;</i></div>-->
            <!--</td>-->
            <td><?php echo $vo['id']; ?></td>
            <td><?php echo $vo['ordersn']; ?></td>
            <td><?php echo $vo['totalprice']; ?></td>
            <td><?php echo $vo['totalprice']; ?></td>
            <td><?php echo $vo['brige_id']; ?></td>
            <td>
                <?php if($vo['status'] == 1): ?>
                未支付
                <?php elseif($vo['status'] == 2): ?>
                微信支付
                <?php else: ?>
                余额支付
                <?php endif; ?>
            </td>
            <td><?php echo $vo['create_time']; ?></td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="page">
        <?php echo $orders->render();; ?>
    </div>

</div>
</body>

</html>