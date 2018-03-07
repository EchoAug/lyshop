<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\product\add.html";i:1508997716;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
    <form class="layui-form" id="product_form">
        <div class="layui-form-item">
            <label for="name" class="layui-form-label">
                <span class="x-red">*</span>商品名称
            </label>
            <div class="layui-input-block">
                <input type="text" id="name" name="name" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>商品分类
            </label>
            <div class="layui-input-block">
                <select name="cid" lay-verify="required">
                    <?php if(is_array($category) || $category instanceof \think\Collection || $category instanceof \think\Paginator): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vo['id']; ?>" ><?php echo $vo['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="price" class="layui-form-label">
                <span class="x-red">*</span>价格
            </label>
            <div class="layui-input-block">
                <input type="text" id="price" name="price" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="cost_price" class="layui-form-label">
                <span class="x-red">*</span>成本价
            </label>
            <div class="layui-input-block">
                <input type="text" id="cost_price" name="cost_price"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="first_img" class="layui-form-label">
                <span class="x-red">*</span>商品图片
            </label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="upload_img">
                    <i class="layui-icon">&#xe67c;</i>上传图片
                </button>
            </div>
            <div class="layui-input-inline" >
                <input class="layui-input" name="first_img" value="" id="first_img">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="keyword" class="layui-form-label">
                <span class="x-red">*</span>关键词
            </label>
            <div class="layui-input-block">
                <input type="text" id="keyword" name="keyword" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>商品描述
            </label>
            <div class="layui-input-block">
                <textarea id="description" name="description" style="display: none;">

                </textarea>
            </div>

        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button class="layui-btn" lay-filter="add" id="additem">
                增加
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['layedit', 'upload', 'form', 'layer'], function () {
        $ = layui.jquery;
        var form = layui.form;
        var layedit = layui.layedit;
        var layer = layui.layer;
        var upload = layui.upload;

        var layEditIndex = layedit.build('description');

        //执行实例
        var uploadInst = upload.render({
            elem: '#upload_img' //绑定元素
            ,url: '<?php echo url("Tool/uploadOne"); ?>' //上传接口
            ,done: function(res){
                //上传完毕回调
                $("#first_img").val(res.data.src);
            }
            ,error: function(){
                //请求异常回调
                layer.msg('上传失败!');
            }
        });
        //监听提交
        $("#additem").click(function(){
            layedit.sync(layEditIndex);
            var product_data = $("#product_form").serializeArray();
            $.post("<?php echo url('Product/saveData'); ?>", product_data, function (res) {
                if(res == 1){
                    layer.alert("修改成功", {icon: 6}, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                }else{
                    layer.msg('修改失败!');
                }
            },'JSON');
            return false;
        });

    });
</script>
</body>

</html>