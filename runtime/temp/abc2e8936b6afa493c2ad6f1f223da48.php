<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\order\recharge.html";i:1514274154;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1518229341;}*/ ?>
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
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">订单管理</a>
        <a href="">订单列表</a>
        <a>
          <cite>充值订单页</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="" method="get">
            <input class="layui-input" placeholder="开始日" name="start" id="start" value="<?php echo $start; ?>">
            ----
            <input class="layui-input" placeholder="截止日" name="end" id="end" value="<?php echo $end; ?>">
            <div class="layui-input-inline">
                <select name="status">
                    <option value="">订单状态</option>
                    <option value="1" <?php if($status == 1): ?> selected <?php endif; ?>>未支付</option>
                    <option value="2" <?php if($status == 2): ?> selected <?php endif; ?>>已支付</option>
                </select>
            </div>
            <input type="text" name="ordercode" placeholder="请输入订单号" autocomplete="off" value="<?php echo $ordercode; ?>"
                   class="layui-input">
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <!--<xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <span class="x-right" style="line-height:40px"></span>
    </xblock>-->
    <table class="layui-table">
        <thead>
        <tr>
            <th>编号</th>
            <th>订单号</th>
            <th>用户openid</th>
            <th>用户手机号</th>
            <th>充值金额</th>
            <th>支付状态</th>
            <th>下单时间</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($orders) || $orders instanceof \think\Collection || $orders instanceof \think\Paginator): $i = 0; $__LIST__ = $orders;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td><?php echo $vo['id']; ?></td>
            <td><?php echo $vo['ordercode']; ?></td>
            <td><?php echo $vo['user']['openid']; ?></td>
            <td>
                <?php if($vo['user']['mobile'] == ''): ?>
                暂无手机号
                <?php else: ?>
                <?php echo $vo['user']['mobile']; endif; ?>
            </td>
            <td><?php echo $vo['amount']; ?></td>
            <td>
                <?php switch($vo['status']): case "2": ?>微信支付<?php break; case "3": ?>后台充值<?php break; default: ?>未支付
                <?php endswitch; ?>
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
<script>
    layui.use('laydate', function () {
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });
    });

    /*用户-停用*/
    function member_stop(obj, id) {
        layer.confirm('确认要停用吗？', function (index) {

            if ($(obj).attr('title') == '启用') {

                //发异步把用户状态进行更改
                $(obj).attr('title', '停用')
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!', {icon: 5, time: 1000});

            } else {
                $(obj).attr('title', '启用')
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!', {icon: 5, time: 1000});
            }

        });
    }

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $.post("<?php echo url('Order/del'); ?>", {"id": id}, function (res) {
                if (res == 1) {
                    layer.msg('已删除!', {icon: 1, time: 1000});
                } else {
                    layer.msg('未删除!', {icon: 2, time: 1000});
                }
            }, 'JSON');
        });
    }


    function delAll(argument) {

        var data = tableCheck.getData();

        layer.confirm('确认要删除吗？', function (index) {
            //捉到所有被选中的，发异步进行删除
            $.post("<?php echo url('Order/batchDel'); ?>", {"ids": data}, function (res) {
                if (res == 1) {
                    layer.msg('已删除!', {icon: 1, time: 1000});
                } else {
                    layer.msg('未删除!', {icon: 2, time: 1000});
                }
            }, 'JSON');
        });
    }
</script>
</body>

</html>