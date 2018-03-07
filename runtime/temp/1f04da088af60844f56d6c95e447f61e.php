<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\ad\payed.html";i:1518230127;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1518229341;}*/ ?>
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
        <a href="">广告管理</a>
        <a href="">广告列表</a>
        <a>
          <cite>支付广告首页</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <xblock>
        <span style="color: palevioletred;font-weight: bold;margin-left: 20px;">
            请只选择一张图片进行冰箱配置，不要选两张或者两张以上的广告。（显示在支付完成后的弹窗）
        </span>
        <div>
            <hr class="layui-bg-green">
            <button class="layui-btn layui-btn-small layui-btn-radius layui-btn-primary"
                    onclick="admin_full_show('支付广告图片添加','<?php echo url("Ad/createPay"); ?>')">
            添加广告 </button>
            <button class="layui-btn layui-btn-small layui-btn-radius layui-btn-normal"
                    id="allot_ad" onmouseover="open_tips()"> 批量配置
            </button>
        </div>
    </xblock>

    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">
                    &#xe605;</i></div>
            </th>
            <th>广告ID</th>
            <th>广告名称</th>
            <th>图片</th>
            <th>上传时间</th>
            <th>广告描述</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($ads) || $ads instanceof \think\Collection || $ads instanceof \think\Paginator): $i = 0; $__LIST__ = $ads;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td>
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id="<?php echo $vo['id']; ?>"><i
                        class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $vo['id']; ?></td>
            <td><?php echo $vo['title']; ?></td>
            <td align="center">
                <?php if($vo['adurl'] == ''): ?>
                <span>暂无图片</span>
                <?php else: ?>
                <div class="ad_img">
                    <img src="__PUBLIC__/ad/<?php echo $vo['adurl']; ?>" style="max-width: 125px;"/>
                </div>
                <?php endif; ?>
            </td>
            <td>
                <?php echo $vo['create_time']; ?>
            </td>
            <td style="max-width: 170px;">
                <?php if($vo['description'] == ''): ?>
                <span class="layui-badge">暂无描述</span>
                <?php else: ?>
                <?php echo $vo['description']; endif; ?>
            </td>
            <td style="width: 240px;">
                <button class="layui-btn layui-btn-small"
                        onclick="x_admin_show('广告详情','<?php echo url("Ad/read",["id"=>$vo['id']]); ?>',530,420)">
                查看描述</button>
                <button class="layui-btn layui-btn-small"
                        onclick="x_admin_show('详情修改','<?php echo url("Ad/edit",["id"=>$vo['id']]); ?>',530,420)">
                编辑信息</button>
                <button class="layui-btn layui-btn-small" onclick="obj_del(this,<?php echo $vo['id']; ?>)">删除广告</button>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; if($ads == null): ?>
        <tr>
            <td colspan="7">暂无数据</td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <div class="page">

    </div>

</div>
<script>
    layui.use(['form', 'layer'], function () {
        var form = layui.form,
                layer = layui.layer;

        //复选框
        $("#allot_ad").click(function () {
            var select_ads = '';
            $(".layui-form-checked").each(function () {
                if ($(this).attr('data-id')) {
                    select_ads += $(this).attr('data-id') + ','
                }
            });
            select_ads = select_ads.substr(0, select_ads.length - 1);
            if (!select_ads) {
                layer.msg('未选择广告!无法配置!', {icon: 2, time: 1000});
                return false;
            }
//            console.log(select_ads);
            //需要修改此处的连接网址
            admin_full_show('配置广告', Config.settingPayAdURL + select_ads);
            return false;
        });
    });

    /*删除*/
    function obj_del(obj, id) {
        layer.confirm('确定删除吗？', function (index) {
            //发异步删除数据
            $.post("<?php echo url('Ad/del'); ?>", {"id": id}, function (res) {
                if (res == 1) {
                    layer.msg('已删除!', {icon: 1, time: 1000});
                } else {
                    layer.msg('未删除!', {icon: 2, time: 1000});
                }
            }, 'JSON');
        });
    }

    var open_tips = function () {
        layer.tips('对所有冰箱进行批量配置支付完成广告', '#allot_ad');
    }
</script>

</body>

</html>