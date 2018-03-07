<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\index\welcome.html";i:1509690752;s:79:"C:\PHPStudyE\WWW\lyshopV2.1\public/../application/admin\view\Public\header.html";i:1510801998;}*/ ?>
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
    <blockquote class="layui-elem-quote">欢迎使用<?php echo config('title'); ?>后台模版！</blockquote>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
        <legend>系统通知</legend>
    </fieldset>

    <div id="dayamount" style="width: 100%;height:300px;">

    </div>

    <fieldset class="layui-elem-field">
        <legend>信息统计</legend>
        <div class="table-responsive">
            <table class="layui-table" lay-even lay-skin="line">
                <colgroup>
                    <col width="40%">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th class="text-center" colspan="2">系统信息</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>网站域名</td>
                    <td><?php echo $config['url']; ?></td>
                </tr>
                <tr>
                    <td>网站目录</td>
                    <td><?php echo $config['document_root']; ?></td>
                </tr>
                <tr>
                    <td>服务器操作系统</td>
                    <td><?php echo $config['server_os']; ?></td>
                </tr>
                <tr>
                    <td>服务器端口</td>
                    <td><?php echo $config['server_port']; ?></td>
                </tr>
                <tr>
                    <td>服务器IP</td>
                    <td><?php echo $config['server_ip']; ?></td>
                </tr>
                <tr>
                    <td>WEB运行环境</td>
                    <td><?php echo $config['server_soft']; ?></td>
                </tr>
                <tr>
                    <td>MySQL数据库版本</td>
                    <td><?php echo $config['mysql_version']; ?></td>
                </tr>
                <tr>
                    <td>运行PHP版本</td>
                    <td><?php echo $config['php_version']; ?></td>
                </tr>

                <tr>
                    <td>最大上传限制</td>
                    <td><?php echo $config['max_upload_size']; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </fieldset>
    <blockquote class="layui-elem-quote layui-quote-nm">年轻是一种资源，但不努力就浪费了。</blockquote>

</div>
</body>
<script src="__STATIC__/js/echarts.common.min.js" charset="utf-8"></script>
<script type="text/javascript">
    $.get('<?php echo url("Index/totalAmount"); ?>', function (res) {
        var dateList = [];
        var dayPriceList = [];
        if (res == 0) {
            layer.msg('暂无数据', function () {
                window.location.href = history.go(-1);
            });
        }
        $.each(res, function (i) {
            dateList[i] = res[i]['daytime'];
            dayPriceList[i] = res[i]['total'];
        });
        productsTotalPriceList(dateList,dayPriceList);
    }, 'JSON');

    function productsTotalPriceList(dateList,dayPriceList) {
        var dayChart = echarts.init(document.getElementById('dayamount'));

        var dayOption = {
            title: {
                text: '今日销售总额（元）'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['营业额']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: dateList
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                name: '营业额',
                type: 'line',
                stack: '总量',
                data: dayPriceList
            }]
        };
        dayChart.setOption(dayOption);
    };
</script>

</html>
