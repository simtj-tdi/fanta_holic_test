@extends('layouts.master')

@section('content')
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">홈</a></li>
        </ol>
        <div class="container-fluid">
            <div id="ui-view">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-xl-3 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body p-3 d-flex align-items-center shadow-sm rounded-sm" style="background-color: #448AFF">
                                    <i class="icon-people p-2 font-2xl ml-1 mr-3 rounded-circle" style="background-color:#fff; color: #448AFF;"></i>
                                    <div>
                                        <div class="text-value-lg" style="color:#fff; font-size: 1.4rem;">
                                            75,652
                                            <span class="text-value-sm" style="color:#fff; font-size: 0.9rem;">+4.3%</span>
                                        </div>
                                        <div class="text-uppercase" style="color:#fff;">총 사용자수</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body p-3 d-flex align-items-center shadow-sm rounded-sm" style="background-color: #F06292">
                                    <i class="icon-user-follow p-2 font-2xl ml-1 mr-3 rounded-circle" style="background-color:#fff; color: #F06292;"></i>
                                    <div>
                                        <div class="text-value-lg" style="color:#fff; font-size: 1.4rem;">
                                            12,452
                                            <span class="text-value-sm" style="color:#fff; font-size: 0.9rem;">+3.7%</span>
                                        </div>
                                        <div class="text-uppercase" style="color:#fff;">신규 사용자수</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body p-3 d-flex align-items-center shadow-sm rounded-sm" style="background-color: #8BC34A">
                                    <i class="icon-screen-smartphone p-2 font-2xl ml-1 mr-3 rounded-circle" style="background-color:#fff; color: #8BC34A;"></i>
                                    <div>
                                        <div class="text-value-lg" style="color:#fff; font-size: 1.4rem;">
                                            452,652
                                            <span class="text-value-sm" style="color:#fff; font-size: 0.9rem;">+7.9%</span>
                                        </div>
                                        <div class="text-uppercase" style="color:#fff;">APP 잔존수</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body p-3 d-flex align-items-center shadow-sm rounded-sm" style="background-color: #FF9800">
                                    <i class="icon-bag p-2 font-2xl ml-1 mr-3 rounded-circle" style="background-color:#fff; color: #FF9800;"></i>
                                    <div>
                                        <div class="text-value-lg" style="color:#fff; font-size: 1.4rem;">
                                            54,289
                                            <span class="text-value-sm" style="color:#fff; font-size: 0.9rem;">-0.9%</span>
                                        </div>
                                        <div class="text-uppercase"  style="color:#fff;">APP 신규설치수</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body shadow-sm rounded-sm">
                                    <h5 class="card-title mb-1">사용자 성별 비율</h5>
                                    <div class="small text-muted ml-0">2019-08</div>

                                    <div id="containerGender" style="width:100%; height:300px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pl-2 pr-2">
                            <div class="card mb-3 border-0" >
                                <div class="card-body shadow-sm rounded-sm">
                                    <h5 class="card-title mb-1">사용자 연령별 비율</h5>
                                    <div class="small text-muted ml-0">2019-08</div>

                                    <div id="containerAge" style="width:100%; height:300px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body shadow-sm rounded-sm">
                                    <h5 class="card-title mb-1">사용자 지역별 비율</h5>
                                    <div class="small text-muted ml-0">2019-08</div>

                                    <div id="containerLocation" style="width:100%; height:300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body shadow-sm rounded-sm">
                                    <h5 class="card-title mb-1">SNS APP</h5>
                                    <div class="small text-muted">2019-08</div>

                                    <div id="container-sns" style="width:100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body shadow-sm rounded-sm">
                                    <h5 class="card-title mb-1">취미 APP</h5>
                                    <div class="small text-muted">2019-08</div>

                                    <div id="container-hobby" style="width:100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body shadow-sm rounded-sm">
                                    <h5 class="card-title mb-1">쇼핑 APP</h5>
                                    <div class="small text-muted">2019-08</div>

                                    <div id="container-shopping" style="width:100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 pl-2 pr-2">
                            <div class="card mb-3 border-0">
                                <div class="card-body shadow-sm rounded-sm">
                                    <h5 class="card-title mb-1">게임 APP</h5>
                                    <div class="small text-muted">2019-08</div>

                                    <div id="container-game" style="width:100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card mb-3 border-0">
                                <div class="card-body shadow-sm rounded-sm">
                                    <h5 class="card-title mb-1">APP 동향</h5>
                                    <div class="small text-muted">2019-08-12 ~ 2019-08-18</div>

                                    <div id="containerApp" style="width:100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('containerGender', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: 0,
                    plotShadow: false
                },
                title: {
                    text: '성별<br>분포',
                    align: 'center',
                    verticalAlign: 'middle',
                    y: 40
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            distance: -50,
                            style: {
                                fontWeight: 'bold',
                                color: 'white'
                            }
                        },
                        startAngle: -90,
                        endAngle: 90,
                        center: ['50%', '75%'],
                        size: '110%'
                    }
                },
                series: [{
                    type: 'pie',
                    name: '비율',
                    colorByPoint: true,
                    innerSize: '50%',
                    data: [
                            @foreach($data['gender'] as $item)
                        ['{{ $item['name'] }}', {{ $item['count'] }}],
                        @endforeach
                    ]
                }],
                colors: [
                    '#448AFF',
                    '#F06292',
                ],
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('containerAge', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: 0,
                    plotShadow: false
                },
                title: {
                    text: '연령별<br>분포',
                    align: 'center',
                    verticalAlign: 'middle',
                    y: 40
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: 'white'
                            }
                        },
                    }
                },
                series: [{
                    type: 'pie',
                    name: '비율',
                    colorByPoint: true,
                    innerSize: '50%',
                    data: [
                            @foreach($data['age'] as $item)
                        ['{{ $item['name'] }}', {{ $item['count'] }}],
                        @endforeach
                    ]
                }],
                colors: [
                    '#689F38',
                    '#7CB342',
                    '#9CCC65',
                    '#AED581',
                    '#C5E1A5',
                    '#DCEDC8',
                    '#F1F8E9',
                ],
                credits: {
                    enabled: false
                },
            });
            Highcharts.chart('containerLocation', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '지역별 분포'
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Percentage (%)'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '비율: <b>{point.y:.1f} %</b>'
                },
                series: [{
                    name: 'Population',
                    colorByPoint: true,
                    data: [
                            @foreach($data['location'] as $item)
                        ['{{ $item['name'] }}', {{ $item['count'] }}],
                        @endforeach
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }],
                colors: [
                    '#EF6C00',
                    '#F57C00',
                    '#FB8C00',
                    '#FF9800',
                    '#FFA726',
                    '#FFB74D',
                    '#FFCC80',
                    '#FFE0B2',
                    '#FFE0B2',
                ],
                credits: {
                    enabled: false
                },
            });
            Highcharts.chart('containerApp', {
                chart: {
                    type: 'areaspline'
                },
                title: {
                    text: 'APP 잔존수 및 신규설치수'
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    verticalAlign: 'top',
                    x: 150,
                    y: 100,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                },
                xAxis: {
                    categories: [
                        '2019-08-12',
                        '2019-08-13',
                        '2019-08-14',
                        '2019-08-15',
                        '2019-08-16',
                        '2019-08-17',
                        '2019-08-18',
                    ],
                },
                yAxis: {
                    title: {
                        text: 'Count (앱설치수)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' units'
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5
                    }
                },
                series: [{
                    name: '잔존수',
                    data: [18642, 21323, 25866, 28090, 30021, 30452, 33254]
                }, {
                    name: '신규설치수',
                    data: [3212, 3608, 3421, 2332, 3909, 301, 3390]
                }],
                colors: [
                    '#B3E5FC',
                    '#0288D1',
                ],
            });

            Highcharts.chart('container-sns', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'SNS APP별 설치수'
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        // rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count (설치수)'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '설치수: <b>{point.y:.1f}</b>'
                },
                series: [{
                    name: 'SNS 종류',
                    colorByPoint: true,
                    data: [
                            @foreach($appData['sns'] as $item)
                        ['{{ $item['name'] }}', {{ $item['count'] }}],
                        @endforeach
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }],
                colors: [
                    //'#1976D2',
                    '#1E88E5',
                    '#2196F3',
                    '#42A5F5',
                    '#64B5F6',
                    '#90CAF9',
                    '#BBDEFB',
                    '#E3F2FD',
                ],
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('container-game', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '게임 APP 카테고리별 설치수'
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        // rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count (설치수)'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '설치수: <b>{point.y:.1f}</b>'
                },
                series: [{
                    name: '게임 종류',
                    colorByPoint: true,
                    data: [
                            @foreach($appData['game'] as $item)
                        ['{{ $item['name'] }}', {{ $item['count'] }}],
                        @endforeach
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }],
                colors: [
                    '#EF6C00',
                  //'#F57C00',
                    '#FB8C00',
                  //'#FF9800',
                    '#FFA726',
                  //'#FFB74D',
                    '#FFCC80',
                    '#FFE0B2',
                    '#FFE0B2',
                ],
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('container-hobby', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '취미 APP 카테고리별 설치수'
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        // rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count (설치수)'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '설치수: <b>{point.y:.1f}</b>'
                },
                series: [{
                    name: '취미 종류',
                    colorByPoint: true,
                    data: [
                            @foreach($appData['hobby'] as $item)
                        ['{{ $item['name'] }}', {{ $item['count'] }}],
                        @endforeach
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }],
                colors: [
                    '#D81B60',
                    '#E91E63',
                    '#EC407A',
                    '#F06292',
                    '#F48FB1',
                    '#F8BBD0',
                    '#FCE4EC',
                ],
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('container-shopping', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '쇼핑 APP 카테고리별 설치수'
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        // rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count (설치수)'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '설치수: <b>{point.y:.1f}</b>'
                },
                series: [{
                    name: '쇼핑 종류',
                    colorByPoint: true,
                    data: [
                            @foreach($appData['shopping'] as $item)
                        ['{{ $item['name'] }}', {{ $item['count'] }}],
                        @endforeach
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }],
                colors: [
                    '#689F38',
                    '#7CB342',
                    '#9CCC65',
                    '#AED581',
                    '#C5E1A5',
                    '#DCEDC8',
                    '#F1F8E9',
                ],
                credits: {
                    enabled: false
                },
            });
        });
    </script>
@endpush
