<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\login\index.html";i:1512717136;s:79:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
<body class="login-bg">

<div class="login">
    <div class="message">LYShop后台登录</div>
    <div id="darkbannerwrap"></div>

    <form method="post" class="layui-form">
        <input name="name" placeholder="用户名" type="text" lay-verify="required" class="layui-input">
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码" type="password" class="layui-input">
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20">
    </form>
</div>

<script>
    layui.use('form', function () {
        var form = layui.form;
        form.on('submit(login)', function (data) {
            $.post('<?php echo url("Login/login"); ?>', {"data": data.field}, function (res) {
                if (res == 16) {
                    layer.msg('登录成功!', {time: 1000, shift: 5}, function () {
                        location.href = '<?php echo url("Agent/Index/index"); ?>';
                    });
                } else if (res == 32) {
                    layer.msg('登录成功!', {time: 1000, shift: 5}, function () {
                        location.href = '<?php echo url("Admin/Index/index"); ?>';
                    });
                } else {
                    layer.msg(res, {time: 2000, shift: 6});
                }
            }, 'JSON');
            return false;
        });
    });
</script>

</body>
</html>