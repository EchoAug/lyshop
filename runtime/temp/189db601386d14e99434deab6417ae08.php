<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"D:\phpStudy\WWW\lyshopV2.1\public/../application/admin\view\brige\productlost.html";i:1508312704;s:78:"D:\phpStudy\WWW\lyshopV2.1\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
<div class="x-body">
    <form class="layui-form">

        <div class="layui-form-item">
            <label for="product_id" class="layui-form-label">
                <span class="x-red">*</span>丢失商品
            </label>
            <div class="layui-input-block">
                <select name="product_id" lay-verify="required" id="product_id">
                    <?php if(is_array($products) || $products instanceof \think\Collection || $products instanceof \think\Paginator): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vo['product_id']; ?>" ><?php echo $vo['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="units" class="layui-form-label">
                <span class="x-red">*</span>丢失数量
            </label>
            <div class="layui-input-block">
                <input type="text" id="units" name="units" required="" lay-verify="number"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <input type="hidden" value="<?php echo input('param.brige_id'); ?>" name="brige_id">
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button class="layui-btn" lay-filter="add" lay-submit="">
                添加丢失记录
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['form', 'layer'], function () {
        $ = layui.jquery;
        var form = layui.form;
        var layer = layui.layer;

        //监听提交
        form.on('submit(add)', function (data) {
            //发异步，把数据提交给php
            $.post("<?php echo url('Brige/saveToLost'); ?>",{"field":data.field},function(res){
                if(res == 1){
                    layer.alert("添加成功", {icon: 6}, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                }else if(res == 3){
                    layer.msg("已经添加过了", {icon: 2});
                } else{
                    layer.msg("添加失败!", {icon: 2});
                }
            }, 'JSON');

            return false;
        });

    });
</script>
</body>

</html>