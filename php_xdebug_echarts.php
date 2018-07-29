<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>ECharts</title>
</head>
<body>
<?php
function makeData($file)
{
    $fp = fopen($file, 'r');
    $arrLineData = [];
    $arrDetailData = [];
    
    while(!feof($fp)){
        $row = fgets($fp);
        $row = trim($row);
        $arr_now = preg_split('#\s+#', $row);
		
        
        if (count($arr_now) == 5) {
			// echo '<pre>';print_r($arr_now); exit();
            $x = $arr_now[0]*10000; //时间消耗, 变成整数, 单位ms*10
            $y = $arr_now[1]; //内存消耗, 缩小数量级, 单位KB
            $arrLineData[] = [$x, $y];
            
            $tmp = [];
            $tmp['time_used']     = $arr_now[0];
            $tmp['memory_used'] = $arr_now[1];
            $tmp['memory_add']     = 0;
            $tmp['function']     = $arr_now[3];
            $tmp['location']     = $arr_now[4];
            $key = $x.'_'.$arr_now[1];
            $arrDetailData[$key] = $tmp;
        }
    }
    
    return [$arrLineData, $arrDetailData];
}

//读取xdebug trace 数据文件
$cpuData = makeData('./aaa.xt');

?>
    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <div id="main" style="height:800px;weight:80%"></div>
    <!-- ECharts单文件引入 -->
    <script src="./echarts.min.js"></script>
    <script type="text/javascript">
    var cpuData = <?= json_encode($cpuData)?>;
    var line1Name = '跟踪';
    
    // 基于准备好的dom，初始化echarts图表
    var myChart = echarts.init(document.getElementById('main')); 
    
    option = {
    title : {
        text: 'PHP效率分析',
        //subtext: '纯属虚构'
    },
    tooltip : {
        trigger: 'axis',
        axisPointer:{
            show: true,
            type : 'cross',
            lineStyle: {
                type : 'dashed',
                width : 1
            }
        },
        formatter : function (params) {
			var x = params[0].value[0];
			var y = params[0].value[1];
			
			var key = x+'_'+y;
			var obj = cpuData[1][key];
			
			str = '';
			str += '时间消耗: '+obj.time_used+"s<br>";
			str += '内存消耗: '+obj.memory_used/1024+"KB<br>";
			// str += '内存增量: '+obj.memory_add+"B<br>";
			str += '函数调用: '+obj.function+"<br>";
			str += '所在行: '+obj.location;
			return str;
            
        }
    },
    dataZoom: {
        show: true,
        start : 0
    },
    legend: {
        data:[line1Name]
    },
    toolbox: {
        show : true,
        feature : {
            dataView : {show: true, readOnly: true},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    xAxis : [
        {
            type: 'value',
            axisLine: {
                lineStyle: {
                    color:'grey',
                    width:1
                }
            }
        }
    ],
    yAxis : [
        {
            type: 'value',
            axisLine: {
                lineStyle: {
                    color:'grey',
                    width:1
                }
            }
        }
    ],
    series : [
        {
            name:line1Name,
            type:'line',
            data:cpuData[0],
        }
    ]
};

        // 为echarts对象加载数据 
        myChart.setOption(option); 
    </script>
</body>
</html>
