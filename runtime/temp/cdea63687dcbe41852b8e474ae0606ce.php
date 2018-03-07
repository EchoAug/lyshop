<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\category\index.html";i:1506413388;s:79:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
        <a href="javasrcipt:;">商品管理</a>
        <a href="javasrcipt:;">商品分类</a>
        <a>
          <cite>分类首页</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="x_admin_show('添加商品分类','<?php echo url("Category/add"); ?>',600,400)"><i class="layui-icon"></i>添加
        </button>
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
            <th>类目名称</th>
            <th>关键词</th>
            <th>添加时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($categories) || $categories instanceof \think\Collection || $categories instanceof \think\Paginator): $i = 0; $__LIST__ = $categories;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td style="text-align: center;">
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $vo['id']; ?>'><i class="layui-icon">
                    &#xe605;</i></div>
            </td>
            <td><?php echo $vo['id']; ?></td>
            <td><?php echo $vo['name']; ?></td>
            <td><?php echo $vo['keyword']; ?></td>
            <td><?php echo $vo['create_time']; ?></td>
            <td class="td-status">
                <span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span></td>
            <td class="td-manage">
                <a onclick="member_stop(this,'10001')" href="javascript:;" title="启用">
                    <i class="layui-icon">&#xe601;</i>
                </a>
                <a title="编辑" onclick="x_admin_show('编辑','<?php echo url("Category/edit",["id" => $vo['id']]); ?>',600,400)" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>
                <a title="删除" onclick="member_del(this,'<?php echo $vo['id']; ?>')" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="page">

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
            $.post("<?php echo url('Category/del'); ?>",{"id": id}, function(res){
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
            $.post("<?php echo url('Category/batchDel'); ?>",{"ids": data}, function(res){
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