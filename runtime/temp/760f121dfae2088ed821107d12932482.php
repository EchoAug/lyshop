<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"D:\phpStudy\WWW\lyshopV2.1\public/../application/admin\view\user\index.html";i:1514274004;s:78:"D:\phpStudy\WWW\lyshopV2.1\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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

</head>
<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">会员管理</a>
        <a href="">会员列表</a>
        <a>
          <cite>首页</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="" method="get">
            <input type="text" name="mobile" placeholder="手机号" autocomplete="off" value="<?php echo $mobile; ?>"
                   class="layui-input">
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>用户ID</th>
            <th>openID</th>
            <th>余额</th>
            <th>手机号</th>
            <th>加入时间</th>
            <th>消费总额</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($users) || $users instanceof \think\Collection || $users instanceof \think\Paginator): $i = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td>
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id="<?php echo $vo['id']; ?>"><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $vo['id']; ?></td>
            <td><?php echo $vo['openid']; ?></td>
            <td>
                <?php if($vo['balance'] == 0): ?>
                0
                <?php else: ?>
                <?php echo $vo['balance']; endif; ?>
                元
            </td>
            <td>
                <?php if($vo['mobile'] == ''): ?>
                    暂未绑定手机号
                <?php else: ?>
                <?php echo $vo['mobile']; endif; ?>
            </td>
            <td><?php echo $vo['create_time']; ?></td>
            <td>
                <?php echo $vo['consumption']; ?>
            </td>
            <td class="td-manage" style="width: 8%;">
                <a title="查看订单" onclick="august_admin_show('订单记录','<?php echo url("User/expenseTracker",["uid" => $vo['id']]); ?>')" href="javascript:;">
                    <i class="layui-icon">&#xe857;</i>
                </a>
                &nbsp;
				<a title="充值" onclick="august_admin_show('充值','<?php echo url("User/recharge",["uid" => $vo['id']]); ?>',400,200)" href="javascript:;">
                    <i class="layui-icon">&#xe65e;</i>
                </a>
                &nbsp;
                <a title="删除" onclick="member_del(this,'<?php echo $vo['id']; ?>')" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="page">
        <?php echo $users->render(); ?>
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
            $.post("<?php echo url('User/del'); ?>",{"id":id}, function(res){
                if(res == 1){
                    layer.msg('已删除!',{icon:1,time:1000});
                } else{
                    layer.msg('未删除!',{icon:2,time:1000});
                }
            }, 'JSON');
        });
    }


    function delAll(argument) {

        var data = tableCheck.getData();

        layer.confirm('确认要删除吗？', function (index) {
            //捉到所有被选中的，发异步进行删除
            $.post("<?php echo url('User/batchDel'); ?>",{"ids":data}, function(res){
                if(res == 1){
                    layer.msg('已删除!',{icon:1,time:1000});
                } else{
                    layer.msg('未删除!',{icon:2,time:1000});
                }
            }, 'JSON');
        });
    }
</script
</body>

</html>