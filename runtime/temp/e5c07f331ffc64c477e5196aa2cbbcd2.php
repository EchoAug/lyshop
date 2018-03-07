<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:77:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\index\index.html";i:1512719182;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1518229341;s:77:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\menu.html";i:1518057116;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\footer.html";i:1506045468;}*/ ?>
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
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="javascript:;"><?php echo config('title'); ?></a></div>
    <div class="left_open">
        <i title="展开左侧栏" class="iconfont">&#xe699;</i>
    </div>
    <ul class="layui-nav left fast-add" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;"> + 新增</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="x_admin_show('商品','<?php echo url("Product/add"); ?>')"><i class="iconfont">&#xe6a2;</i>商品</a></dd>
                <dd><a onclick="x_admin_show('冰箱','<?php echo url("Brige/add"); ?>',550,350)"><i class="iconfont">&#xe6a8;</i>冰箱</a></dd>
            </dl>
        </li>
    </ul>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;"><?php echo session('admin_name'); ?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <!--<dd><a onclick="x_admin_show('个人信息','http://www.baidu.com')">个人信息</a></dd>-->
                <!--<dd><a onclick="x_admin_show('切换帐号','http://www.baidu.com')">切换帐号</a></dd>-->
                <dd><a href="<?php echo url('Login/logout'); ?>">退出登录</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index"><a href="http://www.3ink.cn/shop/index.html">前台首页</a></li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe726;</i>
                    <cite>管理员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('Admin/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>管理员列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('Admin/agent'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>代理人列表</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6b8;</i>
                    <cite>会员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('User/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>会员列表</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe705;</i>
                    <cite>公司管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('Company/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>申请入驻</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe735;</i>
                    <cite>商品管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('Brige/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>冰箱列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('Brige/community'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>公益冰箱</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('Category/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>商品分类</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('Product/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>商品列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('Lost/back'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>补货记录</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('Lost/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>丢失记录</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe723;</i>
                    <cite>订单管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('Order/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>购买订单列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('Order/recharge'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>充值订单列表</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe705;</i>
                    <cite>广告管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('Ad/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>广告列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('Ad/payed'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>支付广告列表</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe705;</i>
                    <cite>反馈管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('Feed/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>留言列表</cite>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowClose="false">
        <ul class="layui-tab-title">
            <li>首页</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src=<?php echo url('index/welcome'); ?> frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2017 新零售</div>
</div>
<!-- 底部结束 -->

</body>
</html>