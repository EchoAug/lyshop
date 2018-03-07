<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\brige\backoverpage.html";i:1509416384;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1518229341;}*/ ?>
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
        <a href="<?php echo url('Brige/index'); ?>">冰箱管理</a>
        <a href="#">补货单列表</a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:window.history.go(-1);" title="返回">
        <i class="layui-icon" style="line-height:30px">&#xe603;</i></a>
</div>
<div class="x-body">
    <xblock>
        <a class="layui-btn layui-btn-danger" href="<?php echo url('Brige/exportExcel',['bid'=>$bid]); ?>" target="_blank">导出补货单</a>
        <a class="layui-btn layui-btn-info" id="finishBack">补货完成</a>
        <span class="x-right" style="line-height:40px"></span>
    </xblock>
    <input type="hidden" id="bid" value="<?php echo $bid; ?>"/>
    <table class="layui-table">
        <thead>
        <tr>
            <th>商品名称</th>
            <th>商品价格</th>
            <th>图片</th>
            <th>商品进价</th>
            <th>补货件数</th>
            <th>日期</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($supplyments) || $supplyments instanceof \think\Collection || $supplyments instanceof \think\Paginator): $i = 0; $__LIST__ = $supplyments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td><?php echo $vo->product_name; ?></td>
            <td><?php echo $vo->price; ?></td>
            <td>
                <img src="__UPLOADS__/<?php echo $vo->product_img; ?>"/>
            </td>
            <td><?php echo $vo->cost_price; ?></td>
            <td><?php echo $vo->fact_units; ?></td>
            <td><?php echo $vo->create_time; ?></td>
            <td>
                <a title="修改补货件数" style="cursor: pointer;"
                   onclick="x_admin_show('修改补货件数','<?php echo url("Brige/supplymentEdit",["id" => $vo->id]); ?>',400,200)">
                    <i class="layui-icon">&#xe642;</i>
                </a>
                &nbsp;
                <a title="删除" onclick="member_del(this,'<?php echo $vo->id; ?>')" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>

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

    $("#finishBack").click(function(){
        var bid = $("#bid").val();
        $.post("<?php echo url('Brige/finishBackOver'); ?>",{"bid":bid},function (res) {
            if (res == 1) {
                layer.msg('补货完成!',{time:1000, shift: 5},function(){
                    location.href = "<?php echo url('Brige/index'); ?>";
                });
            } else {
                layer.msg(res, {icon: 2, time: 1000});
            }
        },'JSON');
    });

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $.post("<?php echo url('Brige/supplymentDel'); ?>", {"id": id}, function (res) {
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
            $.post("<?php echo url('Brige/productBatchDel'); ?>", {"ids": data}, function (res) {
                if (res == 1) {
                    layer.msg('已删除!', {icon: 1, time: 1000});
                } else {
                    layer.msg('删除失败!', {icon: 2, time: 1000});
                }
            }, 'JSON');
        });
    }
</script>

</body>

</html>