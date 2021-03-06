<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\brige\adpage.html";i:1517456536;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>分配广告</title>
    <link rel="stylesheet" href="__STATIC__/css/style.css" media="screen" type="text/css"/>
    <script src="__STATIC__/lib/layui/layui.js"></script>

</head>

<body>

<div class="clearfix">
    <button class="select">&nbsp;</button>
    <h1>配置广告</h1>
    <button id="send_ad" class="send " data-counter="0">&#10004;提交</button>
</div>
<div id="ad_img">
    <ul>
        <?php if(is_array($ads) || $ads instanceof \think\Collection || $ads instanceof \think\Paginator): $i = 0; $__LIST__ = $ads;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <li <?php if(in_array(($vo['id']), is_array($assignedAd)?$assignedAd:explode(',',$assignedAd))): ?> class="selected" <?php endif; ?>>
            <img src="__PUBLIC__/ad/<?php echo $vo['adurl']; ?>" data_id="<?php echo $vo['id']; ?>"/>
        </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<input type="hidden" id="ad_id" name="id" value="<?php echo input('param.bid'); ?>">
</body>

<script src='__STATIC__/js/jquery.js'></script>
<script>
	$(function(){
		// item selection
		$('li').click(function () {
		  $(this).toggleClass('selected');
		  if ($('li.selected').length == 0)
			$('.select').removeClass('selected');
		  else
			$('.select').addClass('selected');
		  counter();
		});

		// all item selection
		$('.select').click(function () {
		  if ($('li.selected').length == 0) {
			$('li').addClass('selected');
			$('.select').addClass('selected');
		  }
		  else {
			$('li').removeClass('selected');
			$('.select').removeClass('selected');
		  }
		  counter();
		});

		// number of selected items
		function counter() {
		  if ($('li.selected').length > 0)
			$('.send').addClass('selected');
		  else
			$('.send').removeClass('selected');
		  $('.send').attr('data-counter',$('li.selected').length);
		}
		
		
		layui.use('layer', function () {
        var layer = layui.layer;

        $("#send_ad").click(function () {
            var ads = '',
                    id = $("#ad_id").val();
            $('#ad_img').find('li').each(function () {
                if ($(this).hasClass('selected')) {

                    var ad_id = $(this).children('img').attr('data_id');
                    ads += ad_id + ",";
                }
            });
            ads = ads.substring(0, ads.length - 1);
            ads = ads.split(',');
            console.log(ads);
            $.post("<?php echo url('Brige/adGrant'); ?>", {"ads": ads, "id": id}, function (res) {
                if (res == 1) {
                    layer.msg("设置广告成功", {icon: 6}, function () {
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
	});
    
</script>

</html>