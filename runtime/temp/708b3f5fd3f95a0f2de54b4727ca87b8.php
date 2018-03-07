<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\user\recharge.html";i:1514273348;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
            <label for="amount" class="layui-form-label">
                <span class="x-red">*</span>充值金额
            </label>
            <div class="layui-input-block">
                <input type="text" id="amount" name="amount" required="" lay-verify="number"
                       placeholder="¥" class="layui-input">
            </div>
        </div>
        <input type="hidden" value="<?php echo input('param.uid'); ?>" name="uid">
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button class="layui-btn" lay-filter="add" lay-submit="">
                充值
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
            console.log(data);
            $.post("<?php echo url('User/doRecharge'); ?>",{"uid":data.field.uid,"money":data.field.amount},function(res){
                if(res.code == 201){
                    layer.alert("充值成功", {icon: 6}, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                } else{
                    layer.msg("充值失败!", {icon: 2});
                }
            }, 'JSON');

            return false;
        });

    });
</script>
</body>

</html>