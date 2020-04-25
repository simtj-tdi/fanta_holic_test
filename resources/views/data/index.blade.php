@extends('layouts.master')

@push('style')
    <style>
        /*body {margin: 10px;}*/
        .where {
            display: block;
            margin: 25px 15px;
            font-size: 11px;
            color: #000;
            text-decoration: none;
            font-family: verdana;
            font-style: italic;
        }

        .checks {position: relative;float: left; margin: 30px}

        .checks input[type="checkbox"] {  /* 실제 체크박스는 화면에서 숨김 */
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip:rect(0,0,0,0);
            border: 0
        }
        .checks input[type="checkbox"] + label {
            display: inline-block;
            position: relative;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        .checks input[type="checkbox"] + label:before {  /* 가짜 체크박스 */
            content: ' ';
            display: inline-block;
            width: 21px;  /* 체크박스의 너비를 지정 */
            height: 21px;  /* 체크박스의 높이를 지정 */
            line-height: 21px; /* 세로정렬을 위해 높이값과 일치 */
            margin: -2px 8px 0 0;
            text-align: center;
            vertical-align: middle;
            background: #fafafa;
            border: 1px solid #cacece;
            border-radius : 3px;
            box-shadow: 0px 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
        }
        .checks input[type="checkbox"] + label:active:before,
        .checks input[type="checkbox"]:checked + label:active:before {
            box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
        }

        .checks input[type="checkbox"]:checked + label:before {  /* 체크박스를 체크했을때 */
            content: '\2714';  /* 체크표시 유니코드 사용 */
            color: #99a1a7;
            text-shadow: 1px 1px #fff;
            background: #e9ecee;
            border-color: #adb8c0;
            box-shadow: 0px 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1);
        }

        .no-csstransforms .checks.etrans input[type="checkbox"]:checked + label:before {
            /*content:"\2713";*/
            content: "\2714";
            top: 0;
            left: 0;
            width: 21px;
            line-height: 21px;
            color: #6cc0e5;
            text-align: center;
            border: 1px solid #6cc0e5;
        }
        .option_box {
            background: #cdcdcd;
            border: 1px solid #cfcfcf;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
            margin:19px 0px 22px 0px;
        }
        .box{
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
            display: inline-block;
            border:1px solid #cccccc;
            background: white;
        }
    </style>

@endpush

@section('content')
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">홈</a>
            </li>
            {{--<li class="breadcrumb-item">콘텐츠</li>--}}
            {{--<li class="breadcrumb-item">데이터 수집량</li>--}}
            <li class="breadcrumb-item active"><strong>데이터 수집량</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="icon-chart"></i></div>
                                {{--<div class="float-right">--}}
                                    {{--<a href="/admin/pushes/create" class="btn btn-success mb-2" role="button">새 게시물</a>--}}
                                    {{--<button id="delete_button" class="btn btn-danger mb-2">삭제</button>--}}
                                {{--</div>--}}
                            </div>
                            <div class="card-body">
                                <form class="form-inline" method="GET" action="{{url('/admin/boards/chart')}}">
                                    <div class="row w-100">
                                        <div class="col-sm-12 col-lg-6">
                                            <h4 class="font-weight-bold mt-3">연동 날짜</h4>
                                            <div class="form-group mb-2">
                                                <input type="text" class="form-control datetimepicker  d-inline-block" id="start_date" name="start_date" value="{{$params['start_date']}}" onchange="submit()">
                                                &nbsp;&nbsp;~&nbsp;&nbsp;
                                                <input type="text" class="form-control datetimepicker  d-inline-block" id="end_date" name="end_date" value="{{$params['end_date']}}" onchange="submit()">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <h4 class="font-weight-bold mt-3">범위</h4>
                                            <select class="form-control mb-2 w-50 d-inline-block" name="range" id="range" onchange="submit()">
                                                <option value="y" {{ ($params['range']=='y') ? 'selected' : '' }}>년 </option>
                                                <option value="m" {{ ($params['range']=='m') ? 'selected' : '' }}>월 </option>
                                                <option value="d" {{ ($params['range']=='d') ? 'selected' : '' }}>일 </option>
                                            </select>

                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <h6 class="font-weight-bold mt-3 mb-3">차트스타일</h6>
                                            <select class="form-control mb-2  w-50 d-inline-block" name ='style' id="style" >
                                                <option value = 'line' >선</option>
                                                <option value = 'bar' >막대</option>
                                            </select>
                                            <button type="submit" class="btn ml-3 mb-2 btn btn-outline-primary">검색</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--<div class="row w-100 option_box mt-0">--}}

        {{--</div>--}}

        <div class="card mr-3">
            <canvas id="myChart" ></canvas>
        </div>

        <div class="row">
            <div class="checks  col-sm-3 card" style="background:rgba(86, 193, 54, 0.2);">
                <input type="checkbox" id="youtube" name ="youtube" value = "youtube" checked>
                <label for="youtube">youtube
                    <div class="row">
                        <div class="col"><h6>Total<h6 id="youtube_total">Total:</h6></h6></div>
                        <div class="col"><h6>Max<h6 id="youtube_max">Max</h6></h6></div>
                        <div class="col"><h6>Min<h6 id="youtube_min">Min</h6></h6></div>
                        <div class="col"><h6>Avg<h6 id="youtube_avg">Avg</h6></h6></div>
                    </div>
                </label>
            </div>

            <div class="checks  col-sm-3  card" style="background:rgba(255, 255, 0, 0.2);">
                <input type="checkbox" id="instagram" name ="instagram" value = "instagram" checked>
                <label for="instagram">instagram
                    <div class="row">
                        <div class="col"><h6>Total<h6 id="instagram_total">Total</h6></h6></div>
                        <div class="col"><h6>Max<h6 id="instagram_max">Max</h6></h6></div>
                        <div class="col"><h6>Min<h6 id="instagram_min">Min</h6></h6></div>
                        <div class="col"><h6>Avg<h6 id="instagram_avg">Avg</h6></h6></div>
                    </div>
                </label>
            </div>

            <div class="checks  col-sm-3 card" style="background:rgba(0, 0, 0, 0.2);">
                <input type="checkbox" id="news" name ="news" value = "news" checked>
                <label for="news">news
                    <div class="row">
                        <div class="col"><h6>Total<h6 id="news_total">Total</h6></h6></div>
                        <div class="col"><h6>Max<h6 id="news_max">Max</h6></h6></div>
                        <div class="col"><h6>Min<h6 id="news_min">Min</h6></h6></div>
                        <div class="col"><h6>Avg<h6 id="news_avg">Avg</h6></h6></div>
                    </div>
                </label>
            </div>

            <div class="checks col-sm-3  card" style="background:rgba(255, 0, 255, 0.2);">
                <input type="checkbox" id="web" name="web" value = "web" checked>
                <label for="web">web
                    <div class="row">
                        <div class="col"><h6>Total<h6 id="web_total">Total</h6></h6></div>
                        <div class="col"><h6>Max<h6 id="web_max">Max</h6></h6></div>
                        <div class="col"><h6>Min<h6 id="web_min">Min</h6></h6></div>
                        <div class="col"><h6>Avg<h6 id="web_avg">Avg</h6></h6></div>
                    </div>
                </label>
            </div>
        </div>
    </main>
@endsection


@push('script')
    <script>
        function sum(array) {
            var result = 0.0;

            for (var i = 0; i < array.length; i++)
                result += array[i];

            return result;
        }

        var ctx = document.getElementById('myChart').getContext('2d');
        ctx.canvas.width = 1000;
        ctx.canvas.height = 500;

        $(document).ready(function() {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var range = $('#range').val();
            var mychart;
            var news_data;
            var instagram_data;
            var youtube_data;
            var web_data;
            var data;
            var config;

            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });
            $.ajax({
                url: "/admin/boards/chart/data",
                method:"get",
                data : {
                    'start_date':start_date,
                    'end_date': end_date,
                    'range':range
                },
                dataType: "json"
            }).done(function (result) {
                console.log(result);
                data = {
                    labels: result.date,
                    datasets: []
                };
                var types = ['youtube','instagram','news','web'];

                for(var i  in types){
                    var count = [];
                    for(var k=0;k<result.data[types[i]].length;k++) {
                        count.push(result.data[types[i]][k]['count']);
                    }
                    console.log(sum(count)/count.length);
                    var total_target_id=types[i]+'_total';
                    $('[id='+total_target_id+']').html(sum(count));

                    var Max_target_id=types[i]+'_max';
                    console.log(Max_target_id);
                    var max = count.reduce( function (previous, current) {
                        return previous > current ? previous:current;
                    });
                    $('[id='+Max_target_id+']').html(max);

                    var Min_target_id=types[i]+'_min';
                    var min = count.reduce( function (previous, current) {
                        return previous > current ? current:previous;
                    });
                    $('[id='+Min_target_id+']').html(min);

                    var Avg_target_id=types[i]+'_avg';
                    var avg = sum(count)/count.length;
                    avg = avg.toFixed(2);
                    $('[id='+Avg_target_id+']').html(avg);


                    this[types[i]+'_data'] = {
                        label:types[i],
                        borderColor:'rgba(99, 99, 132, 1)',
                        borderWidth: 1,
                        data: count
                    };
                    if(types[i]=='news'){
                        this[types[i]+'_data'].backgroundColor = 'rgba(0, 0, 0, 0.2)';
                    }else if(types[i]=='youtube'){
                        this[types[i]+'_data'].backgroundColor = 'rgba(86, 193, 54, 0.2)';
                    }else if(types[i]=='instagram'){
                        this[types[i]+'_data'].backgroundColor = 'rgba(255, 255, 0, 0.2)';
                    }else if(types[i]=='web'){
                        this[types[i]+'_data'].backgroundColor = 'rgba(255, 0, 255, 0.2)';
                    }
                    data.datasets.push(this[types[i]+'_data'])
                }
                config = {
                    type: 'bar',
                    data: data,
                    options:{
                        legend:{
                            display:false
                        },
                        tooltips:{
                          mode:'index',
                          intersect:false,
                        },
                        hover:{
                          mode:'nearest',
                          intersect:true
                        },
                        scales:{
                            yAxes:[{
                                display:true,
                                ticks:{
                                    // fontSize: 2,
                                    // fontColor: 'transparent',
                                    min:0,
                                    stepsize:10
                                }
                            }]
                        },
                        elements: {
                            line: {
                                borderWidth: 1
                            },
                            point: {
                                radius: 4,
                                hitRadius: 10,
                                hoverRadius: 4
                            }
                        }
                    }
                };
                mychart = new Chart(ctx,config);
                //선으로 시작할시 막대로 변환할시 마지막 데이터가 짤려서 보임
                //일단 막대로 그리고 선으로 전환함;수정필요
                var temp_config=mychart.config;
                mychart.destroy();
                var temp = jQuery.extend(true, {},temp_config);
                temp.type = 'line';
                mychart = new Chart(ctx, temp);
                //
            });

            $("#youtube").change(function(){
                if($("#youtube").is(":checked")){
                    mychart.data.datasets.push(youtube_data);
                    mychart.update();
                }else{
                    //youtube 데이터 제거
                    var index=mychart.data.datasets.findIndex(function(e){
                        return e.label == 'youtube';
                    });
                    youtube_data = mychart.data.datasets.splice(index,1)[0];
                    mychart.update();
                }
            });

            $("#instagram").change(function(){
                if($("#instagram").is(":checked")){
                    mychart.data.datasets.push(instagram_data);
                    mychart.update();
                }else{
                    //instagram 데이터 제거
                    var index=mychart.data.datasets.findIndex(function(e){
                        return e.label == 'instagram';
                    });
                    instagram_data = mychart.data.datasets.splice(index,1)[0];
                    mychart.update();
                }
            });



            $("#news").change(function(){
                if($("#news").is(":checked")){
                    mychart.data.datasets.push(news_data);
                    mychart.update();
                }else{
                    //news 데이터 제거
                    var index=mychart.data.datasets.findIndex(function(e){
                        return e.label == 'news';
                    });
                    news_data = mychart.data.datasets.splice(index,1)[0];
                    mychart.update();
                }
            });

            $("#web").change(function(){
                if($("#web").is(":checked")){
                    mychart.data.datasets.push(web_data);
                    mychart.update();
                }else{
                    //web 데이터 제거
                    var index=mychart.data.datasets.findIndex(function(e){
                        return e.label == 'web';
                    });
                    web_data = mychart.data.datasets.splice(index,1)[0];
                    mychart.update();
                }

            });

            $('#style').change(function(event){
                var style = event.target.value;
                // Remove the old chart and all its event handles
                var temp_config=mychart.config;
                mychart.destroy();
                var temp = jQuery.extend(true, {},temp_config);
                temp.type = style;
                mychart = new Chart(ctx, temp);
            });
        });
    </script>
@endpush
