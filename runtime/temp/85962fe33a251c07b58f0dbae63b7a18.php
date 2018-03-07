<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\brige\stockeditpage.html";i:1514183442;s:79:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
            <label for="stock" class="layui-form-label">
                <span class="x-red">*</span>库存量
            </label>
            <div class="layui-input-block">
                <input type="text" id="stock" name="stock" required="" lay-verify="number"
                       autocomplete="off" class="layui-input" value="<?php echo $stock['stock']; ?>">
            </div>
        </div>
        <input type="hidden" value="<?php echo $stock['id']; ?>" name="id">
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button class="layui-btn" lay-filter="add" lay-submit="">
                修改库存量
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
            $.post("<?php echo url('Brige/stockEdit'); ?>", {"stock": data.field.stock, "id": data.field.id}, function (res) {
                if (res == 1) {
                    layer.alert("修改成功", {icon: 6}, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                } else {
                    layer.msg("修改失败!", {icon: 2});
                }
            }, 'JSON');

            return false;
        });

    });
</script>
</body>

</html>