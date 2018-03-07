<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\brige\communityadd.html";i:1512032834;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
            <label for="communityID" class="layui-form-label">
                <span class="x-red">*</span>冰箱编号
            </label>
            <div class="layui-input-inline">
                <select name="communityID" id="communityID" lay-verify="required" lay-search="">
                    <option value="">直接选择或搜索选择</option>
                    <?php if(is_array($briges) || $briges instanceof \think\Collection || $briges instanceof \think\Paginator): $i = 0; $__LIST__ = $briges;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vo['brige_id']; ?>"><?php echo $vo['code']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>冰箱编号
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button class="layui-btn" lay-filter="add" lay-submit="">
                增加
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
            $.post("<?php echo url('Brige/createCommunity'); ?>", {"communityID": data.field.communityID}, function(res){
                if(res == 1){
                    layer.alert("增加成功", {icon: 6}, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                } else{
                    layer.msg('增加失败');
                }
            }, 'JSON');
            return false;
        });


    });
</script>
</body>

</html>