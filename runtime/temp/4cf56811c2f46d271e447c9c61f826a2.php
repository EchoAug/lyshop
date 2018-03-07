<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\brige\edit.html";i:1511404160;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
            <label for="code" class="layui-form-label">
                <span class="x-red">*</span>冰箱编号
            </label>
            <div class="layui-input-inline">
                <input type="text" id="code" name="code" required=""
                       autocomplete="off" class="layui-input" value="<?php echo $brige['code']; ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>冰箱编号
            </div>
        </div>
        <div class="layui-form-item">
            <label for="address" class="layui-form-label">
                <span class="x-red">*</span>冰箱地址
            </label>
            <div class="layui-input-inline">
                <input type="text" id="address" name="address" required=""
                       autocomplete="off" class="layui-input" value="<?php echo $brige['address']; ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>冰箱地址
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>冰箱介绍
            </label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" id="description" name="description"><?php echo $brige['description']; ?></textarea>
            </div>
        </div>
        <input type="hidden" value="<?php echo $brige['brige_id']; ?>" name="brige_id">
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button class="layui-btn" lay-filter="add" lay-submit="">
                修改
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
            console.log(data);
            //发异步，把数据提交给php
            $.post("<?php echo url('Brige/updateEdit'); ?>", {"field": data.field}, function(res){
                if(res == 1){
                    layer.alert("修改成功", {icon: 6}, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                } else{
                    layer.msg('修改失败');
                }
            }, 'JSON');
            return false;
        });


    });
</script>
</body>

</html>