<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\brige\index.html";i:1514196074;s:79:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
        <a href="javasrcipt:;">冰箱列表</a>
        <a>
          <cite>冰箱首页</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="x_admin_show('添加冰箱','<?php echo url("Brige/add"); ?>',550,350)"><i class="layui-icon"></i>添加冰箱
        </button>
        <span style="line-height:40px;margin-left:36px;">总营业额: ¥ <span style="color: #FF5722;font-size: 20px;"><?php echo $allTheSum; ?></span>元</span>
        <span style="line-height:40px">冰箱总价值: ¥ <span style="color: #FF5722;font-size: 20px;"><?php echo $residue; ?></span>元</span>
        <span class="x-right" style="line-height:40px">今日总营业额: ¥ <span style="color: #FF5722;font-size: 20px;"><?php if($allsum != ''): ?><?php echo $allsum; else: ?> 0 <?php endif; ?></span>元</span>
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
            <th>总营业额</th>
            <th>今日营业额</th>
            <th>冰箱商品总价</th>
            <th>冰箱描述</th>
            <th>是否缺货</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($fridges) || $fridges instanceof \think\Collection || $fridges instanceof \think\Paginator): $i = 0; $__LIST__ = $fridges;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td style="text-align: center;">
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{vo.id}'><i class="layui-icon">
                    &#xe605;</i></div>
            </td>
            <td><?php echo $vo['brige_id']; ?></td>
            <td><?php echo $vo['code']; ?></td>
            <td><?php echo $vo['address']; ?></td>
            <td>¥ <span style="color: #FF5722;"><?php echo $vo['turnover_money']; ?></span></td>
            <td>
                ¥
                <?php if($vo['order_today'] == ''): ?>
                <span style="color: #FF5722;">0</span>
                <?php else: ?>
                <span style="color: #FF5722;"><?php echo $vo['order_today']; ?></span>
                <?php endif; ?>
            </td>
            <td>¥ <span style="color: #5FB878;"><?php echo $vo['products_total']['total']; ?></span></td>
            <td><span style="color: #2a493c;"><?php echo $vo['description']; ?></span></td>
            <td class="td-status">
                <?php if($vo['products_total']['isBack'] == 1): ?>
                <span class="layui-btn layui-btn-danger layui-btn-mini">已缺货</span>
                <?php else: ?>
                <span class="layui-btn layui-btn-normal layui-btn-mini">暂不缺货</span>
                <?php endif; ?>
            </td>
            <td style="width: 15%;">
                <!--<a onclick="member_stop(this,'10001')" href="javascript:;" title="启用">-->
                    <!--<i class="layui-icon">&#xe601;</i>-->
                <!--</a>&nbsp;&nbsp;&nbsp;-->
                <a class="operation-icon" title="查看产品" href="<?php echo url("Brige/product",["id" => $vo['brige_id']]); ?>">
                <i class="layui-icon">&#xe698;</i>
                </a>
                <a class="operation-icon" title="信息修改"  onclick="x_admin_show('冰箱信息修改','<?php echo url("Brige/edit",["brige_id" => $vo['brige_id']]); ?>',550,350)" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
                </a>
                <a class="operation-icon" title="查看补货单" href='<?php echo url("Brige/backOverPage",["bid" => $vo['brige_id']]); ?>' >
                    <i class="layui-icon">&#xe63c;</i>
                </a>
                <a class="operation-icon" title="销量统计" href='<?php echo url("Brige/sellCharts",["brige_id" => $vo['brige_id']]); ?>'>
                <i class="layui-icon">&#xe629;</i>
                </a>
                <a class="operation-icon" title="记录丢失商品" onclick="x_admin_show('商品丢失记录','<?php echo url("Brige/ProductLost",["brige_id" => $vo['brige_id']]); ?>',550,250)"
                    href="javascript:;">
                    <i class="layui-icon">&#xe6b2;</i>
                </a>
                <a class="operation-icon" title="生成二维码" onclick="august_admin_show('冰箱二维码','<?php echo url("Brige/qrCode",["bid" => $vo['brige_id']]); ?>',400,400)"
                href="javascript:;">
                <img style="width: 16px;vertical-align: top; " src="__STATIC__/images/qrcode_icon.png"/>
                </a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="page">
        <?php echo $fridges->render();; ?>
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
            $.post("<?php echo url('Brige/del'); ?>",{"id": id}, function(res){
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
            $.post("<?php echo url('Brige/batchDel'); ?>",{"ids": data}, function(res){
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