<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\phpStudy\WWW\lyshopV2.1\public/../application/admin\view\ad\allotad.html";i:1517391144;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>分配广告</title>
    <link rel="stylesheet" href="__STATIC__/css/style.css" media="screen" type="text/css"/>
    <script src="__STATIC__/lib/layui/layui.js"></script>

</head>

<body>
<div class="ad_selections">
    <div class="clearfix">
        <button class="select">&nbsp;</button>
        <h1>配置默认广告方案</h1>
        <button id="send_ad" class="send " data-counter="0">&#10004;提交</button>
    </div>
    <div id="ad_img">
        <ul>
            <?php if(is_array($fridges) || $fridges instanceof \think\Collection || $fridges instanceof \think\Paginator): $i = 0; $__LIST__ = $fridges;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li data-id="<?php echo $vo['brige_id']; ?>">
                <h3><?php echo $vo['code']; ?></h3>
                <span><?php echo $vo['address']; ?></span>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
</div>
<input id="ads" type="hidden" name="ads" value="<?php echo $ads; ?>">
</body>

<script src='__STATIC__/js/jquery.js'></script>
<script src='__STATIC__/js/assign_ad.js'></script>
<script>
    layui.use(['form','layer'], function () {
        var layer = layui.layer;

        $("#send_ad").click(function () {
            var fridges = '',
                    ads = $("#ads").val();
            $('#ad_img').find('li').each(function () {
                if ($(this).hasClass('selected')) {
                    var fridge_id = $(this).attr('data-id');
                    fridges += fridge_id + ",";
                }
            });
            fridges = fridges.substring(0, fridges.length - 1);
            fridges = fridges.split(',');
            ads = ads.split(',');
            console.log(ads);
            console.log(fridges);
            $.post("<?php echo url('Ad/allotAdToFridge'); ?>", {"ads": ads, "fridges": fridges}, function (res) {
                if (res == 1) {
                    layer.msg("批量设置广告成功", {icon: 6}, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                } else {
                    layer.msg("设置广告失败,请重试!", {icon: 2});
                }
            }, 'JSON');

        });
    });
</script>

</html>