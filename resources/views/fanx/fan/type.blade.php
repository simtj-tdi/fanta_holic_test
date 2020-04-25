@extends('layouts.master')

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">홈</a></li>
        <li class="breadcrumb-item active">팬 유형분석</li>
    </ol>
    <div class="container-fluid">
        <div id="ui-view">
            <div class="animated fadeIn">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="card-title mb-0">성별 분석</h4>
                                <div class="small text-muted">2019-08</div>
                            </div>

                            <div class="col-sm-6 d-none d-md-block">
                                <button class="btn btn-outline-primary float-right" type="button"  data-toggle="modal" data-target="#modalGenderHistory">
                                    History <i class="fa fa-angle-double-right fa-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-7" style="padding: 2em;">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="callout callout-warning">
                                            <small class="text-muted">User</small>
                                            <br>
                                            <strong class="h4">278,623</strong>
                                            <div class="chart-wrapper">
                                                <canvas id="sparkline-chart-3" width="100" height="30"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="callout callout-success">
                                            <small class="text-muted">Active User</small>
                                            <br>
                                            <strong class="h4">149,123</strong>
                                            <div class="chart-wrapper">
                                                <canvas id="sparkline-chart-4" width="100" height="30"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="progress-group">
                                    <div class="progress-group-header">
                                        <i class="icon-user progress-group-icon"></i>
                                        <div>남성</div>
                                        <div class="ml-auto font-weight-bold">28.9%</div>
                                    </div>
                                    <div class="progress-group-bars">
                                        <div class="progress progress-xs">
                                            <div class="progress-bar" role="progressbar" style="width: 28.9%; background-color: #0091EA;" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-group mb-5">
                                    <div class="progress-group-header">
                                        <i class="icon-user-female progress-group-icon"></i>
                                        <div>여성</div>
                                        <div class="ml-auto font-weight-bold">71.1%</div>
                                    </div>
                                    <div class="progress-group-bars">
                                        <div class="progress progress-xs">
                                            <div class="progress-bar" role="progressbar" style="width: 71.1%; background-color: #CE93D8;" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div id="containerGender" style="width:100%; height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="card-title mb-0">연령별 분석</h4>
                                <div class="small text-muted">2019-08</div>
                            </div>

                            <div class="col-sm-6 d-none d-md-block">
                                    <button class="btn btn-outline-primary float-right" type="button"  data-toggle="modal" data-target="#modalAgeHistory">
                                    History <i class="fa fa-angle-double-right fa-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-7">
                                <div id="containerAge" style="width:100%; height: 400px;"></div>
                            </div>
                            <div class="col-lg-5">
                                <div id="containerAgeGroup" style="width:100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modalGenderHistory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">성별 분포 History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="containerGenderHistory" style="width:100%; height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgeHistory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">연령 분포 History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="col-md-1 col-form-label" for="select1">월</label>
                        <div class="col-md-4">
                            <select class="form-control" id="select1" name="select1">
                                <option value="2019-08">2019-08</option>
                                <option value="2019-07">2019-07</option>
                                <option value="2019-06">2019-06</option>
                                <option value="2019-05">2019-05</option>
                                <option value="2019-04">2019-04</option>
                                <option value="2019-03">2019-03</option>
                                <option value="2019-02">2019-02</option>
                                <option value="2019-01">2019-01</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div id="containerAgeHistory" style="width:100%; height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
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
                    '#0091EA',
                    '#CE93D8',
                ],
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('containerAge', {
                chart: {
                    type: 'area'
                },
                title: {
                    text: '각 연령별 분포'
                },
                xAxis: {
                    allowDecimals: false,
                    labels: {
                        formatter: function () {
                            return this.value; // clean, unformatted number for year
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Count (명)'
                    },
                    labels: {
                        formatter: function () {
                            return this.value / 1000 + 'k';
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{point.y:,.0f} 명'
                },
                plotOptions: {
                    area: {
                        pointStart: 10,
                        marker: {
                            enabled: false,
                            symbol: 'circle',
                            radius: 2,
                            states: {
                                hover: {
                                    enabled: true
                                }
                            }
                        }
                    }
                },
                series: [{
                    name: '연령',
                    data: [
                        20434, 24126, 27387, 29459, 31056, 31982, 32040, 31233, 29224, 27342,
                        26662, 26956, 27912, 28999, 28965, 27826, 25579, 25722, 24826, 24605,
                        24304, 23464, 23708, 24099, 24357, 24237, 24401, 24344, 23586, 22380,
                        21004, 17287, 14747, 13076, 12555, 12144, 11009, 10950, 10871, 10824,
                        10577, 10527, 10475, 10421, 10358, 10295, 10104, 9914, 9620, 9326,
                    ]
                }],
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('containerAgeGroup', {
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
                    '#0277BD',
                    '#0288D1',
                    '#0091EA',
                    '#039BE5',
                    '#03A9F4',
                    '#00B0FF',
                    '#29B6F6',
                    '#40C4FF',
                    '#4FC3F7',
                    '#81D4FA',
                    '#80D8FF',
                    '#B3E5FC',
                    '#E3F2FD',
                    '#E1F5FE',
                    '#E0F7FA',
                    '#E0F2F1',
                    '#E8EAF6'
                ],
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('containerGenderHistory', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '성별 분포'
                },
                subtitle: {
                    text: '2019-01 ~ 2019-08'
                },
                xAxis: {
                    categories: [
                        '2019-01',
                        '2019-02',
                        '2019-03',
                        '2019-04',
                        '2019-05',
                        '2019-06',
                        '2019-07',
                        '2019-08',
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '성별 분포'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: '남성',
                    data: [22, 29, 32.5, 27.9, 25.1, 28.4, 31.5, 28.9]

                }, {
                    name: '여성',
                    data: [78, 71, 67.5, 72.1, 74.9, 71.6, 68.5, 71.1]

                }],
                colors: [
                    '#0091EA',
                    '#CE93D8',
                ],
                credits: {
                    enabled: false
                },
            });

            var ageHistoryChart = Highcharts.chart('containerAgeHistory', {
                chart: {
                    type: 'area'
                },
                title: {
                    text: '연령 분포'
                },
                xAxis: {
                    allowDecimals: false,
                    labels: {
                        formatter: function () {
                            return this.value; // clean, unformatted number for year
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Count (명)'
                    },
                    labels: {
                        formatter: function () {
                            return this.value / 1000 + 'k';
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{point.y:,.0f} 명'
                },
                plotOptions: {
                    area: {
                        pointStart: 10,
                        marker: {
                            enabled: false,
                            symbol: 'circle',
                            radius: 2,
                            states: {
                                hover: {
                                    enabled: true
                                }
                            }
                        }
                    }
                },
                series: [{
                    name: '연령',
                    data: [
                        20434, 24126, 27387, 29459, 31056, 31982, 32040, 31233, 29224, 27342,
                        26662, 26956, 27912, 28999, 28965, 27826, 25579, 25722, 24826, 24605,
                        24304, 23464, 23708, 24099, 24357, 24237, 24401, 24344, 23586, 22380,
                        21004, 17287, 14747, 13076, 12555, 12144, 11009, 10950, 10871, 10824,
                        10577, 10527, 10475, 10421, 10358, 10295, 10104, 9914, 9620, 9326,
                    ]
                }],
                credits: {
                    enabled: false
                },
            });

            $( "#select1" ).change(function() {
                var arr = [
                        20434, 24126, 27387, 29459, 31056, 31982, 32040, 31233, 29224, 27342,
                        26662, 26956, 27912, 28999, 28965, 27826, 25579, 25722, 24826, 24605,
                        24304, 23464, 23708, 24099, 24357, 24237, 24401, 24344, 23586, 22380,
                        21004, 17287, 14747, 13076, 12555, 12144, 11009, 10950, 10871, 10824,
                        10577, 10527, 10475, 10421, 10358, 10295, 10104, 9914, 9620, 9326,
                    ];

                if(this.value !== '2019-08') {
                    arr = arr.map(function(item){
                        return item + Math.floor(Math.random() * (2000 + 2000)) - 2000;
                    });
                }

                ageHistoryChart.series[0].setData(arr);
            });
        });
    </script>
@endpush
