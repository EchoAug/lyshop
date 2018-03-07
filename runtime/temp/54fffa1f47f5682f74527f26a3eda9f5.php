<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\lost\back.html";i:1509003706;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1518229341;}*/ ?>
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
        <a href="javasrcipt:;">商品管理</a>
        <a href="javasrcipt:;">补货记录</a>
        <a>
          <cite>记录首页</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <span class="x-right" style="line-height:40px"></span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th style="width: 5%;text-align: center;">
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">
                    &#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>冰箱编号</th>
            <th>冰箱地址</th>
            <th>补货价值</th>
            <th>补货进价</th>
            <th>补货时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($backs) || $backs instanceof \think\Collection || $backs instanceof \think\Paginator): $i = 0; $__LIST__ = $backs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td style="text-align: center;">
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $vo['id']; ?>'><i class="layui-icon">
                    &#xe605;</i></div>
            </td>
            <td><?php echo $vo['id']; ?></td>
            <td><?php echo $vo['brige']['code']; ?></td>
            <td><span style="color: #393D49;"><?php echo $vo['brige']['address']; ?></span></td>
            <td>¥ <span style="color: #5FB878;"><?php echo $vo['back_sum']; ?></span></td>
            <td>
                ¥
                <span style="color: #FF5722;">
                    <?php echo $vo['cost_sum']; ?>
                </span>
            </td>
            <td>
                <?php echo $vo['create_time']; ?>
            </td>
            <td class="td-manage" style="width: 5%;">
                <a title="删除" onclick="member_del(this,'<?php echo $vo['id']; ?>')" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a>
                <a title="查看货单详情" onclick="x_admin_show('补货单详情','<?php echo url("Lost/backDetail",["id"=>$vo['id']]); ?>',1000,600)" href="javascript:;">
                <i class="layui-icon">&#xe63c;</i>
                </a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="page">
        <?php echo $backs->render();; ?>
    </div>

</div>
<script>
    layui.use('laydate', function () {
        $ = layui.jquery;
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

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $.post("<?php echo url('Lost/suDel'); ?>",{"id": id}, function(res){
                if(res == 1){
                    layer.msg('已删除!', {icon: 1, time: 1000});
                } else{
                    layer.msg('删除失败!', {icon: 2, time: 1000});
                }
            }, 'JSON');

        });
    }


    function delAll(argument) {

        var data = tableCheck.getData();

        layer.confirm('确认要删除吗？', function (index) {
            //捉到所有被选中的，发异步进行删除
            $.post("<?php echo url('Lost/suBatchDel'); ?>",{"ids": data}, function(res){
                if(res == 1){
                    layer.msg('删除成功', {icon: 1});
                }else{
                    layer.msg('删除失败', {icon: 2});
                }
            }, 'JSON');
        });
    }
</script>
</body>

</html>