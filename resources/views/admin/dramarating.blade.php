@extends('app')

@section('title', '剧集评分详情 - 管理页 - ')

@section('css')
    <script src="//cdn.bootcss.com/echarts/3.3.2/echarts.min.js"></script>
@endsection

@section('content')
    <div class="container">
        <h4>《{{ $drama->title }}》（{{ $drama->sc }}）</h4>
        <p>收藏人数：{{ count($favorites) }}</p>
        <p>
            <?php $types = ['想听', '在追', '听过', '搁置', '抛弃']; ?>
            @foreach($favorites->groupBy('type')->sort() as $type => $favorites)
                {{ $types[$type] }}：{{ count($favorites) }}人&nbsp;&nbsp;
            @endforeach
        </p>
        <p>评分人数：{{ $count }}&nbsp;&nbsp;平均分：{{ $average }}</p>
        <p>
        <div id="main" style="width: 600px;height:400px;"></div>
            <script>
                var myChart = echarts.init(document.getElementById('main'));
                var option = {
                    title: {
                        text: '评分分布（单位：人数）'
                    },
                    xAxis: {
                        data: ['半星','一星','一星半','二星','二星半','三星','三星半','四星','四星半','五星']
                    },
                    tooltip: {},
                    yAxis: {},
                    series: [{
                        name: '人数',
                        type: 'bar',
                        label: {
                            normal: {
                                show: true,
                                position: 'top'
                            }
                        },
                        data: [@for($rating = 1; $rating < 10; $rating++){{ $ratings[$rating] }},@endfor{{ $ratings[10] }}]
                    }]
                };
                myChart.setOption(option);
            </script>
        </p>
    </div>
@endsection
