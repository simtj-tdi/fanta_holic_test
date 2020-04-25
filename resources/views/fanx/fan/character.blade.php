@extends('layouts.master')

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">홈</a></li>
        <li class="breadcrumb-item active">팬 성향분석</li>
    </ol>
    <div class="container-fluid">
        <div id="ui-view">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-0">SNS 분석</h4>
                                <div class="small text-muted">2019-08</div>

                                <div id="container-sns" style="width:100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-0">취미 분석</h4>
                                <div class="small text-muted">2019-08</div>

                                <div id="container-hobby" style="width:100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-0">쇼핑 분석</h4>
                                <div class="small text-muted">2019-08</div>

                                <div id="container-shopping" style="width:100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-0">게임 분석</h4>
                                <div class="small text-muted">2019-08</div>

                                <div id="container-game" style="width:100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body overflow-hidden">
                                <h4 class="card-title mb-0">SNS App 랭킹 Top5</h4>
                                <div class="small text-muted" style="margin-bottom: 1.5em;">2019-08</div>

                                <table class="table table-responsive-sm text-center">
                                    <thead>
                                        <tr>
                                        <th>No.</th>
                                        <th>App명</th>
                                        <th>카테고리</th>
                                        <th>총설치수</th>
                                        <th>유저설치수</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tableData['sns'] as $item)
                                        <tr>
                                            <td>{{ $item['id'] }}</td>
                                            <td class="text-left">
                                                <img src="{{ $item['image'] }}" width="32" height="32">
                                                <span class="ml-1">{{ $item['name'] }}</span>
                                            </td>
                                            <td>{{ $item['category'] }}</td>
                                            <td class="text-right">
                                                <strong>{{ $item['installed'] }}</strong>
                                                <br>
                                                <span class="{{ $item['trend'] == 'up' ? 'text-danger' : 'text-primary' }}">{{ $item['differ'] }}</span>
                                            </td>
                                            <td class="text-right">
                                                <strong>{{ $item['newInstall'] }}</strong>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body overflow-hidden">
                                <h4 class="card-title mb-0">취미 App 랭킹 Top5</h4>
                                <div class="small text-muted" style="margin-bottom: 1.5em;">2019-08</div>

                                <table class="table table-responsive-sm text-center">
                                    <thead>
                                        <tr>
                                        <th>No.</th>
                                        <th>App명</th>
                                        <th>카테고리</th>
                                        <th>총설치수</th>
                                        <th>유저설치수</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tableData['hobby'] as $item)
                                        <tr>
                                            <td>{{ $item['id'] }}</td>
                                            <td class="text-left text-truncate">
                                                <img src="{{ $item['image'] }}" width="32" height="32">
                                                <span class="ml-1">{{ $item['name'] }}</span>
                                            </td>
                                            <td class="text-truncate">{{ $item['category'] }}</td>
                                            <td class="text-right">
                                                <strong>{{ $item['installed'] }}</strong>
                                                <br>
                                                <span class="{{ $item['trend'] == 'up' ? 'text-danger' : 'text-primary' }}">{{ $item['differ'] }}</span>
                                            </td>
                                            <td class="text-right">
                                                <strong>{{ $item['newInstall'] }}</strong>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body overflow-hidden">
                                <h4 class="card-title mb-0">쇼핑 App 랭킹 Top5</h4>
                                <div class="small text-muted" style="margin-bottom: 1.5em;">2019-08</div>

                                <table class="table table-responsive-sm text-center">
                                    <thead>
                                        <tr>
                                        <th>No.</th>
                                        <th>App명</th>
                                        <th>카테고리</th>
                                        <th>총설치수</th>
                                        <th>유저설치수</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tableData['shopping'] as $item)
                                        <tr>
                                            <td>{{ $item['id'] }}</td>
                                            <td class="text-left">
                                                <img src="{{ $item['image'] }}" width="32" height="32">
                                                <span class="ml-1">{{ $item['name'] }}</span>
                                            </td>
                                            <td>{{ $item['category'] }}</td>
                                            <td class="text-right">
                                                <strong>{{ $item['installed'] }}</strong>
                                                <br>
                                                <span class="{{ $item['trend'] == 'up' ? 'text-danger' : 'text-primary' }}">{{ $item['differ'] }}</span>
                                            </td>
                                            <td class="text-right">
                                                <strong>{{ $item['newInstall'] }}</strong>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body overflow-hidden">
                                <h4 class="card-title mb-0">게임 App 랭킹 Top5</h4>
                                <div class="small text-muted" style="margin-bottom: 1.5em;">2019-08</div>

                                <table class="table table-responsive-sm text-center">
                                    <thead>
                                        <tr>
                                        <th>No.</th>
                                        <th>App명</th>
                                        <th>카테고리</th>
                                        <th>총설치수</th>
                                        <th>유저설치수</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tableData['game'] as $item)
                                        <tr>
                                            <td>{{ $item['id'] }}</td>
                                            <td class="text-left">
                                                <img src="{{ $item['image'] }}" width="32" height="32">
                                                <span class="ml-1">{{ $item['name'] }}</span>
                                            </td>
                                            <td>{{ $item['category'] }}</td>
                                            <td class="text-right">
                                                <strong>{{ $item['installed'] }}</strong>
                                                <br>
                                                <span class="{{ $item['trend'] == 'up' ? 'text-danger' : 'text-primary' }}">{{ $item['differ'] }}</span>
                                            </td>
                                            <td class="text-right">
                                                <strong>{{ $item['newInstall'] }}</strong>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
            Highcharts.setOptions({
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
                ]
            });

            Highcharts.chart('container-sns', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '팬 SNS 성향 분석'
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
                        @foreach($data['sns'] as $item)
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
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('container-game', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '팬 게임 성향 분석'
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
                        @foreach($data['game'] as $item)
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
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('container-hobby', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '팬 취미 성향 분석'
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
                        @foreach($data['hobby'] as $item)
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
                credits: {
                    enabled: false
                },
            });

            Highcharts.chart('container-shopping', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '팬 쇼핑 성향 분석'
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
                        @foreach($data['shopping'] as $item)
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
                credits: {
                    enabled: false
                },
            });
        });
    </script>
@endpush
