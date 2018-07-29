# PHP-xdubge 效率分析
用echarts和php代码分析xdebug跟踪文件, 生成PHP运行效率的折线图, 简单明了, 信息丰富

1. 用xdebug生成trace文件(不是profiler)
2. 将文件名改为aaa.xt, 并放到php文件的同一级
3. 将百度的echarts.min.js也放到php文件的同一级
4. 搭建好web环境, 访问php文件
5. 将鼠标放到折线图的小圆点上, 会显示出那一点的运行情况, 耗费时间/耗费内存/代码所在行

> 注意, 两点之间越陡, 说明内存增长比较快, 两点之间越宽说明耗费CPU很多
