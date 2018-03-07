<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:81:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\brige\community.html";i:1512033454;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1518229341;}*/ ?>
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
        <a href="javasrcipt:;">公益管理</a>
        <a href="javasrcipt:;">公益冰箱列表</a>
        <a>
          <cite>首页</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <xblock>
        <button class="layui-btn" onclick="x_admin_show('添加冰箱','<?php echo url("Brige/communityAdd"); ?>',550,350)">
            <i class="layui-icon"></i>添加公益冰箱
        </button>
        <span class="x-right"
              style="line-height:40px;color: #FF5722;font-size: 14px;">操作提醒：*****公益冰箱只能添加已经有的冰箱****</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>冰箱编号</th>
            <th>冰箱地址</th>
            <th>总营业额</th>
            <th>今日营业额</th>
            <th>公益基金（日）</th>
            <th>冰箱商品总价</th>
            <th>冰箱描述</th>
            <th>是否缺货</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($briges) || $briges instanceof \think\Collection || $briges instanceof \think\Paginator): $i = 0; $__LIST__ = $briges;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td><?php echo $vo['brige_id']; ?></td>
            <td><?php echo $vo['code']; ?></td>
            <td><?php echo $vo['address']; ?></td>
            <td>¥ <span style="color: #FF5722;"><?php echo $vo['money']; ?></span></td>
            <td>
                ¥
                <?php if($vo['dayliSum'] == ''): ?>
                <span style="color: #FF5722;">0</span>
                <?php else: ?>
                <span style="color: #FF5722;"><?php echo $vo['dayliSum']; ?></span>
                <?php endif; ?>
            </td>
            <td>¥ <span style="color: #FF5722;"><?php echo $vo['community']; ?></span></td>
            <td>¥ <span style="color: #5FB878;"><?php echo $vo['residue']; ?></span></td>
            <td><span style="color: #2a493c;"><?php echo $vo['description']; ?></span></td>
            <td class="td-status">
                <?php if($vo['isBackOver'] == 1): ?>
                <span class="layui-btn layui-btn-danger layui-btn-mini">已缺货</span>
                <?php else: ?>
                <span class="layui-btn layui-btn-normal layui-btn-mini">暂不缺货</span>
                <?php endif; ?>
            </td>
            <td style="width: 15%;">
                <a class="operation-icon" onclick="member_stop(this,'<?php echo $vo['brige_id']; ?>')" href="javascript:;" title="停用">
                    <i class="layui-icon">&#x1007;</i>
                </a>
                <a class="operation-icon" title="查看产品" href="<?php echo url("Brige/product",["id" => $vo['brige_id']]); ?>">
                <i class="layui-icon">&#xe698;</i>
                </a>
                <a class="operation-icon" title="信息修改" onclick="x_admin_show('冰箱信息修改','<?php echo url(" Brige/edit",["brige_id"
                => $vo['brige_id']]); ?>',550,350)" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
                </a>
                <a class="operation-icon" title="查看补货单" href='<?php echo url("Brige/backOverPage",["bid" => $vo['brige_id']]); ?>'>
                    <i class="layui-icon">&#xe63c;</i>
                </a>
                <a class="operation-icon" title="销量统计" href='<?php echo url("Brige/sellCharts",["brige_id" => $vo['brige_id']]); ?>'>
                    <i class="layui-icon">&#xe629;</i>
                </a>
                <a class="operation-icon" title="记录丢失商品" onclick="x_admin_show('商品丢失记录','<?php echo url(" Brige/ProductLost",["brige_id"
                => $vo['brige_id']]); ?>',550,250)"
                href="javascript:;">
                <i class="layui-icon">&#xe6b2;</i>
                </a>
                <a class="operation-icon" title="生成二维码" onclick="august_admin_show('冰箱二维码','<?php echo url(" Brige/qrCode",["bid"
                => $vo['brige_id']]); ?>',400,400)"
                href="javascript:;">
                <img style="width: 16px;vertical-align: top; " src="__STATIC__/images/qrcode_icon.png"/>
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
            $.post("<?php echo url('Brige/stopCommunity'); ?>", {"id": id}, function (res) {
                if (res == 1) {
                    layer.msg('已停用!', {icon: 6, time: 1000});
                } else {
                    layer.msg('停用失败',{icon: 5, time: 1000});
                }
            }, 'JSON');
            return false;
        });
    }

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $.post("<?php echo url('Brige/del'); ?>", {"id": id}, function (res) {
                if (res == 1) {
                    layer.msg('已删除!', {icon: 1, time: 1000});
                } else {
                    layer.msg('删除失败!', {icon: 2, time: 1000});
                }
            }, 'JSON');

        });
    }


    function delAll(argument) {

        var data = tableCheck.getData();

        layer.confirm('确认要删除吗？', function (index) {
            //捉到所有被选中的，发异步进行删除
            $.post("<?php echo url('Brige/batchDel'); ?>", {"ids": data}, function (res) {
                if (res == 1) {
                    layer.msg('删除成功', {icon: 1});
                } else {
                    layer.msg('删除失败', {icon: 2});
                }
            }, 'JSON');
        });
    }
</script>
</body>

</html>