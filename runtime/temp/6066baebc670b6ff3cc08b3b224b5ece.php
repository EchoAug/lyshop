<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\brige\sellcharts.html";i:1508221694;s:79:"C:\PHPStudyE\WWW\lyshopV2.2\public/../application/admin\view\Public\header.html";i:1518229341;}*/ ?>
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
<div class="x-body">
    <div class="x-nav">
          <span class="layui-breadcrumb">
              <a href="javasrcipt:;">商品管理</a>
              <a href="javasrcipt:;">冰箱列表</a>
              <a>
                <cite>销售情况</cite>
              </a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
           href="javascript:location.replace(location.href);" title="刷新">
            <i class="layui-icon" style="line-height:30px">ဂ</i>
        </a>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
           href="javascript:window.history.go(-1);" title="返回">
            <i class="layui-icon" style="line-height:30px">&#xe603;</i>
        </a>
    </div>
    <input type="hidden" value="<?php echo input('param.brige_id'); ?>" id="brige_id"/>
    <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
    <blockquote class="layui-elem-quote">
        冰箱日销售额
    </blockquote>
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="" method="get">
            <input class="layui-input" placeholder="开始日" name="start" id="start" >
            ----
            <input class="layui-input" placeholder="截止日" name="end" id="end">
            <button class="layui-btn" lay-submit="" lay-filter="check"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <div id="dayamount" style="width: 100%;height:300px;">

    </div>
    <blockquote class="layui-elem-quote">
        冰箱货品库存量
    </blockquote>
    <div id="stock" style="width: 100%;height:300px;"></div>
    <blockquote class="layui-elem-quote">
        冰箱货品销售比例
    </blockquote>
    <div id="rating" style="width: 100%;height:300px;">

    </div>

</div>
<script src="__STATIC__/js/echarts.common.min.js" charset="utf-8"></script>
<script>
    layui.use(['form','laydate'], function () {
        var laydate = layui.laydate;
        var form = layui.form;

        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });

        //监听提交
        form.on('submit(check)', function(data){
            console.log(data.field);
            var brige_id = $("#brige_id").val();
            $.get('<?php echo url("Brige/ajaxDaysAmount"); ?>',{"brige_id": brige_id,"start": data.field.start,"end":data.field.end},function(res){
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
            },'JSON');
            return false;
        });
    });
</script>
<script type="text/javascript">
    var brige_id = $("#brige_id").val();
    $.get('<?php echo url("Brige/ajaxSelling"); ?>', {"brige_id": brige_id}, function (res) {
        var dataAxis = [];
        var data = [];
        var datastock = [];
        if (res == 0) {
            layer.msg('暂无数据', function () {
                window.location.href = history.go(-1);
            });
        }
        $.each(res, function (i) {
            dataAxis[i] = res[i]['name'];
            data[i] = res[i]['percent'];
            datastock[i] = res[i]['stock'];
        });
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('rating'));

        var yMax = 100;
        var dataShadow = [];

        for (var i = 0; i < data.length; i++) {
            dataShadow.push(yMax);
        }

        option = {
            title: {
                text: '货品销售比例',
                subtext: '冰箱中每个商品销售百分比比例'
            },
            xAxis: {
                data: dataAxis,
                axisLabel: {
                    inside: true,
                    textStyle: {
                        color: '#1A1A1A'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLine: {
                    show: false
                },
                z: 10
            },
            yAxis: {
                axisLine: {
                    show: false
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    textStyle: {
                        color: '#999'
                    }
                }
            },
            dataZoom: [
                {
                    type: 'inside'
                }
            ],
            series: [
                { // For shadow
                    type: 'bar',
                    itemStyle: {
                        normal: {color: 'rgba(0,0,0,0.05)'}
                    },
                    barGap: '-100%',
                    barCategoryGap: '40%',
                    data: dataShadow,
                    animation: false
                },
                {
                    type: 'bar',
                    itemStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(
                                    0, 0, 0, 1,
                                    [
                                        {offset: 0, color: '#83bff6'},
                                        {offset: 0.5, color: '#188df0'},
                                        {offset: 1, color: '#188df0'}
                                    ]
                            )
                        },
                        emphasis: {
                            color: new echarts.graphic.LinearGradient(
                                    0, 0, 0, 1,
                                    [
                                        {offset: 0, color: '#2378f7'},
                                        {offset: 0.7, color: '#2378f7'},
                                        {offset: 1, color: '#83bff6'}
                                    ]
                            )
                        }
                    },
                    data: data
                }
            ]
        };

        // Enable data zoom when user click bar.
        var zoomSize = 6;
        myChart.on('click', function (params) {
            console.log(dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)]);
            myChart.dispatchAction({
                type: 'dataZoom',
                startValue: dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)],
                endValue: dataAxis[Math.min(params.dataIndex + zoomSize / 2, data.length - 1)]
            });
        });
        myChart.setOption(option);

        var stockChart = echarts.init(document.getElementById('stock'));

        stockOption = {
            color: ['#3398DB'],
            tooltip: {
                trigger: 'axis',
                axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                    type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    data: dataAxis,
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name: '库存量(件)',
                    type: 'bar',
                    barWidth: '60%',
                    data: datastock
                }
            ]
        };
        stockChart.setOption(stockOption);
    }, 'JSON');
    $.get('<?php echo url("Brige/ajaxDaysAmountInit"); ?>', {"brige_id": brige_id}, function (res) {
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
                text: '销售额报表（元）'
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
</body>
</html>