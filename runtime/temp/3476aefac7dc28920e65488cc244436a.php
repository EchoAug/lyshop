<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"D:\phpStudy\WWW\lyshopV2.1\public/../application/admin\view\brige\product.html";i:1514799674;s:78:"D:\phpStudy\WWW\lyshopV2.1\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
        <a href="<?php echo url('Brige/index'); ?>">冰箱管理</a>
        <a href="/">商品列表</a>
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
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="x_admin_show('添加货品','<?php echo url("Brige/productAdd",['id' => $id]); ?>',600,300)">
            <i class="layui-icon"></i>添货
        </button>
        <button class="layui-btn" id="backover" value="<?php echo $id; ?>">
            <i class="layui-icon">&#xe63c;</i>补货
        </button>
        <span class="x-right" style="line-height:40px">共有数据：<?php echo count($products); ?> 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">
                    &#xe605;</i></div>
            </th>
            <th>商品名称</th>
            <th>原始价格</th>
            <th>成本价格</th>
            <th>商品图片</th>
            <th>初始库存量</th>
            <th>库存量</th>
            <th>库存预警量</th>
            <th>总销售量</th>
            <th>关键词</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($products) || $products instanceof \think\Collection || $products instanceof \think\Paginator): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td>
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $vo['pivot']['id']; ?>'><i
                        class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $vo['name']; ?></td>
            <td><?php echo $vo['price']; ?></td>
            <td><?php echo $vo['cost_price']; ?></td>
            <td>
                <img src="__UPLOADS__/<?php echo $vo['first_img']; ?>"/>
            </td>
            <td>
                <?php echo $vo['pivot']['init_stock']; ?>
            </td>
            <td>
                <?php if($vo['pivot']['stock'] <= $vo['pivot']['floor_stock']): ?>
                    <label style="color: #FF5722;font-size: 20px;font-weight: bold;">
                        <?php echo $vo['pivot']['stock']; ?>
                    </label>
                <?php else: ?>
                    <label style="font-size: 14px;">
                        <?php echo $vo['pivot']['stock']; ?>
                    </label>
                <?php endif; ?>
            </td>
            <td>
                <?php echo $vo['pivot']['floor_stock']; ?>
            </td>
            <td style="color: mediumpurple;">
                <?php echo $vo['pivot']['sales_sum']; ?>
            </td>
            <td><?php echo $vo['keyword']; ?></td>
            <td class="td-manage">
                <a title="修改库存量" style="cursor: pointer;"
                   onclick="x_admin_show('修改库存量','<?php echo url("Brige/stockEditPage",["brige_id" => $id,"product_id" => $vo['product_id']]); ?>',400,200)">
                <i class="layui-icon">&#xe6b2;</i>
                </a>
                &nbsp;
                <a title="删除" onclick="member_del(this,'<?php echo $vo['pivot']['id']; ?>')" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a>
                &nbsp;
                <a title="修改初始库存" style="cursor: pointer;"
                   onclick="x_admin_show('修改初始库存','<?php echo url("Brige/productEdit",["brige_id" => $id,"product_id" => $vo['product_id']]); ?>',400,200)">
                <i class="layui-icon">&#xe642;</i>
                </a>
                &nbsp;
                <a title="修改库存预警" style="cursor: pointer;"
                   onclick="x_admin_show('修改库存预警','<?php echo url("Brige/floorShow",["brige_id" => $id,"product_id" => $vo['product_id']]); ?>',400,200)">
                <i class="layui-icon">&#xe628;</i>
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

    $("#backover").click(function(){
        var bid = $(this).val();
        layer.confirm('生成补货单吗？', function (index) {
            $.post("<?php echo url('Brige/backOver'); ?>",{"bid":bid},function (res) {
                if (res == 1) {
                    layer.msg('生成补货单成功!',{time:1000, shift: 5},function(){
                        location.href = "<?php echo url('Brige/index'); ?>";
                    });
                } else {
                    layer.msg(res, {icon: 2, time: 1000});
                }
            },'JSON');
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
            $.post("<?php echo url('Brige/productDel'); ?>", {"id": id}, function (res) {
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