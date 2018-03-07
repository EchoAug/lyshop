<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:72:"D:\phpStudy\WWW\lyshopV2.1\public/../application/admin\view\ad\read.html";i:1517277107;s:78:"D:\phpStudy\WWW\lyshopV2.1\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
            <label for="title" class="layui-form-label">
                <span class="x-red">*</span>广告名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="title" name="title" required=""
                       autocomplete="off" class="layui-input" value="<?php echo $ad['title']; ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>广告名称
            </div>
        </div>
        <div class="layui-form-item">
            <label for="description" class="layui-form-label">
                <span class="x-red">*</span>广告描述
            </label>
            <div class="layui-input-inline">
                <textarea style="width: 185px;height: 280px;" class="layui-textarea" id="description" name="description"><?php echo $ad['description']; ?></textarea>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>广告描述
            </div>
        </div>
    </form>
</div>
</body>

</html>