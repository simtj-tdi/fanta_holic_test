@extends('layouts.master')

@push('script')
    <style>
        .custom-control-label::before {
            left: 0.35rem;
            top: 0.35rem;
        }
        .custom-control-label::after {
            left: 0.35rem;
            top: 0.35rem;
        }
        #columns figure input{
            position:absolute;
            top: 10px;
        }
        /*a button {*/
            /*background: #7f7f7f;*/
            /*color: white;*/
        /*}*/a
        label, input { display:block; }
        input.text { margin-bottom:12px; width:95%; padding: .4em; }
        #alertbox { padding:10px; border:0; margin-top:25px;border-radius: 10px; }
        .ui-widget-header {
            border:none;
            background:white;
        }

        .text-checking {
            z-index:900;
            position : fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .ui-dialog-titlebar-close {
            position: absolute;
            right: .3em;
            top: 50%;
            width: 20px;
            margin: 0 0 0 0;
            padding: 1px;
            height: 20px;
            border-radius: 10px;
        }

        .keyword,
        .account,
        .tag {
            /*float:left;*/
            margin:3px 3px 7px 20px;
            position:relative;
            font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size:0.75em;
            font-weight:bold;
            text-decoration:none;
            color:#996633;
            text-shadow:0px 1px 0px rgba(255,255,255,.4);
            padding:0.417em 0.417em 0.417em 0.917em;
            border-top:1px solid #d99d38;
            border-right:1px solid #d99d38;
            border-bottom:1px solid #d99d38;
            -webkit-border-radius:0 0.25em 0.25em 0;
            -moz-border-radius:0 0.25em 0.25em 0;
            border-radius:0 0.25em 0.25em 0;
            background-image: -webkit-linear-gradient(top, rgb(254, 218, 113), rgb(254, 186, 71));
            background-image: -moz-linear-gradient(top, rgb(254, 218, 113), rgb(254, 186, 71));
            background-image: -o-linear-gradient(top, rgb(254, 218, 113), rgb(254, 186, 71));
            background-image: -ms-linear-gradient(top, rgb(254, 218, 113), rgb(254, 186, 71));
            background-image: linear-gradient(top, rgb(254, 218, 113), rgb(254, 186, 71));
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#feda71', EndColorStr='#feba47');
            -webkit-box-shadow:
                    inset 0 1px 0 #faeaba,
                    0 1px 1px rgba(0,0,0,.1);
            -moz-box-shadow:
                    inset 0 1px 0 #faeaba,
                    0 1px 1px rgba(0,0,0,.1);
            box-shadow:
                    inset 0 1px 0 #faeaba,
                    0 1px 1px rgba(0,0,0,.1);
            z-index:100;
        }
        .keyword,
        .account{
            border-left:1px solid #d99d38;
        }
        .keyword:hover,
        .account:hover,
        .tag:hover {
            background-image: -webkit-linear-gradient(top, rgb(254, 225, 141), rgb(254, 200, 108));
            background-image: -moz-linear-gradient(top, rgb(254, 225, 141), rgb(254, 200, 108));
            background-image: -o-linear-gradient(top, rgb(254, 225, 141), rgb(254, 200, 108));
            background-image: -ms-linear-gradient(top, rgb(254, 225, 141), rgb(254, 200, 108));
            background-image: linear-gradient(top, rgb(254, 225, 141), rgb(254, 200, 108));
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#fee18d', EndColorStr='#fec86c');
            border-color:#e1b160;
        }
        .keyword:hover:before,
        .account:hover:before,
        .tag:hover:before {
            background-image: -webkit-linear-gradient(left top, rgb(254, 225, 141), rgb(254, 200, 108));
            background-image: -moz-linear-gradient(left top, rgb(254, 225, 141), rgb(254, 200, 108));
            background-image: -o-linear-gradient(left top, rgb(254, 225, 141), rgb(254, 200, 108));
            background-image: -ms-linear-gradient(left top, rgb(254, 225, 141), rgb(254, 200, 108));
            background-image: linear-gradient(left top, rgb(254, 225, 141), rgb(254, 200, 108));
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=1,StartColorStr='#fee18d', EndColorStr='#fec86c');
            border-color:#e1b160;
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
        @if (session('alert'))
            @php echo "<script>alert('".session('alert')."');</script>"; @endphp
        @endif
         <div class="row option_box mt-0 mb-0">
             <div class="col-md-7 box">
                 <h4 class="text-primary font-weight-bold mt-1">수집 조건</h4>
                 <form method="POST" id="crawling_execute_form" action="{{url('/admin/collect_batches/bulk/execute')}}">
                     {{ csrf_field() }}
                 </form>
                 <form method="POST" name = "input_form" id="input_form" onsubmit="loading()" action="{{url('/admin/collect_batches')}}">
                     {{ csrf_field() }}
                     <input type="hidden" id="board" name="board" value="{{$params['board']}}">
                     <input type="hidden" id="type" name="type" value="{{$params['type']}}">
                     <input type="hidden" id="search" name="search" value="{{$params['search']}}">
                     <input type="hidden" id="gender" name="gender" value="{{$params['gender']}}">
                     <div class="row">
                         <div class="col-2 form-group mt-3">
                             <select class="form-control" name="board" id='board' style="width: 100%;" onchange="boardChange(this.value);" >
                                 <option value="instagram" {{($params['board']=='instagram')? 'selected' : ''}}>instagram</option>
                                 <option value="youtube"  {{($params['board']=='youtube')? 'selected' : ''}}>youtube</option>
                             </select>
                         </div>
                         <div class="col-3 form-group mt-3">
                             <select class="form-control" name="type" id='type' style="width: 100%;" >
                                 @if($params['board']=='instagram')
                                     <option value="hashtag" {{($params['type']=='hashtag')? 'selected' : ''}} disabled>hashtag</option>
                                     <option value="account" {{($params['type']=='account')? 'selected' : ''}}>account</option>
                                 @else
                                     <option value="keyword" {{($params['type']=='keyword')? 'selected' : ''}}>keyword</option>
                                     <option value="channel" {{($params['type']=='channel')? 'selected' : ''}}>channel</option>
                                 @endif
                             </select>
                         </div>
                         <div class="col-2 form-group mt-3">
                             <select class="form-control" name="gender" id="gender" style="width:100%">
                                 <option value="1" {{($params['gender']=='1')? 'selected' : ''}}>남자</option>
                                 <option value="2" {{($params['gender']=='2')? 'selected' : ''}}>여자</option>
                             </select>
                         </div>
                         <div class="col-5 form-group mt-3"  id="input">
                             <div class="row">
                                 <div class="col-9">
                                     <crawling_input_autocomplete :items= '{{ $all_tag_list }}' />
                                 </div>
                                 <div class="col-3">
                                     <button type="submit" class="btn btn-primary mb-2" id="btn_create" name="btn_create" >등록</button>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </form>
             </div>
             <div class="col-md-5 box">
                 {{--<h4 class="text-primary font-weight-bold mt-1">인스타그램 옵션(안씀)</h4>--}}
                 <h4 class="text-primary font-weight-bold mt-1">크롤링 상태</h4>
                     {{--<form method="POST" name = "standard_form" id="standard_form" action="{{url('/admin/collect_rules')}}">--}}
                         {{--<input type="hidden" name="_method" value="put">--}}
                         {{--{{ csrf_field() }}--}}
                     <div class="row">
                         <div class="col">
                             <div class="row">
                                 <div class="col-7">
                                     <span class="nav-icon" data-feather="youtube"></span> &nbsp;youtube
                                 </div>
                                 <div class="col-5">
                                     @if($youtube_crawling_state)
                                         <span class="nav-icon icon-control-play"></span> 정상 수집중
                                     @else
                                         <span class="nav-icon icon-control-pause"></span> 오류 발생
                                     @endif
                                 </div>
                             </div>
                             <hr class="my-2">
                             <div class="row">
                                 <div class="col-7">
                                     <span class="nav-icon" data-feather="instagram"></span>&nbsp;instagram
                                 </div>
                                 <div class="col-5">
                                     @if($instagram_account_crawling_state)
                                         <span class="nav-icon icon-control-play"></span> 정상 수집중
                                     @else
                                         <span class="nav-icon icon-control-pause"></span> 오류({{$instagram_account_crawling_error_id}})
                                     @endif
                                 </div>
                             </div>
                         </div>
                         <div class="col">
                             {{--<div>d</div>--}}
                             {{--<hr class="my-2">--}}
                             {{--<div>t</div>--}}
                         </div>
                          {{--<div class="col form-group">--}}
                              {{--<h6 class="font-weight-bold">조회수</h6>--}}
                              {{--<input class="w-100" type="number" value="{{$standards->view_cnt}}" name="view_cnt">--}}
                          {{--</div>--}}
                          {{--<div class="col form-group">--}}
                              {{--<h6 class="font-weight-bold">좋아요 수</h6>--}}
                              {{--<input class="w-100" type="number" value="{{$standards->like_cnt}}" name="like_cnt">--}}
                          {{--</div>--}}
                          {{--<div class="col form-group">--}}
                              {{--<h6 class="font-weight-bold">수집 게시물 수</h6>--}}
                              {{--<input class="w-100" type="number" value="{{$standards->get_cnt}}" name="get_cnt">--}}
                          {{--</div>--}}
                          {{--<div class="col-4 form-group mt-3">--}}
                              {{--<div class="row">--}}
                                  {{--<div class="col-md-6 pr-0">--}}
                                      {{--<button type="submit" class="btn btn-info" >수정</button>--}}
                                  {{--</div>--}}
                                  {{--<div class="col-md-6 pl-0">--}}
                                      {{--@if ($batch_status==1)--}}
                                          {{--<button type="button" class="btn btn-secondary"  disabled >배치 실행중</button>--}}
                                      {{--@else--}}
                                          {{--<button type="button" name="crawling_execute" class="btn btn-primary ml-0">배치 실행</button>--}}
                                      {{--@endif--}}
                                  {{--</div>--}}
                              {{--</div>--}}
                          {{--</div>--}}
                     </div>
                 {{--</form>--}}

             </div>
         </div>
        <form class="form-inline row option_box mt-0" name="search_form" id="search_form" method="GET" action="{{url('/admin/collect_batches')}}">
            <input type="hidden" id="board" name="board" value="{{$params['board']}}">
            <input type="hidden" id="type" name="type" value="{{$params['type']}}">
            <input type="hidden" id="search" name="search" value="{{$params['search']}}">
            <input type="hidden" id="gender" name="gender" value="{{$params['gender']}}">
            <div class="col-2 box">
                <h4 class="font-weight-bold mt-1">플랫폼</h4>
                <div class="form-group row custom-radio">
                    <div class="col-6 mb-1">
                        <button type="radio" class="btn {{($params['board']=='instagram')? 'btn-primary' : 'btn-outline-primary'}} col-md-12" name="board" value="instagram">instagram</button>
                    </div>
                    <div class="col-6 mb-1">
                        <button type="radio" class="btn {{($params['board']=='youtube')? 'btn-primary' : 'btn-outline-primary'}} col-md-12" name="board" value="youtube">youtube</button>
                    </div>
                </div>
            </div>
            <div class="col-3 box">
                <h4 class="font-weight-bold mt-1">타입</h4>
                <div class="form-group row custom-radio">
                    @if($params['board']=='instagram')
                        <div class="col mb-1">
                            <button type="radio" class="btn {{($params['type']=='account')? 'btn-primary' : 'btn-outline-primary'}} col-md-12" name="type" value="account">계정</button>
                        </div>
                        <div class="col mb-1">
                            <button type="radio" class="btn {{($params['type']=='hashtag')? 'btn-primary' : 'btn-outline-primary'}} col-md-12" name="type" value="hashtag" disabled>해시태그(작업중)</button>
                        </div>
                    @else
                        <div class="col mb-1">
                            <button type="radio" class="btn {{($params['type']=='channel')? 'btn-primary' : 'btn-outline-primary'}} col-md-12" id="type-1" name="type" value="channel" >채널</button>
                        </div>
                        <div class="col mb-1">
                            <button type="radio" class="btn {{($params['type']=='keyword')? 'btn-primary' : 'btn-outline-primary'}} col-md-12" id="type-1" name="type" value="keyword">키워드</button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-2 box">
                <h4 class="font-weight-bold mt-1">타겟</h4>
                <div class="form-group row custom-radio">
                    <div class="col mb-1">
                        <button type="radio" class="btn {{($params['gender']=='1')? 'btn-primary' : 'btn-outline-primary'}} col-md-12" name="gender" value="1">남자</button>
                    </div>
                    <div class="col mb-1">
                        <button type="radio" class="btn {{($params['gender']=='2')? 'btn-primary' : 'btn-outline-primary'}} col-md-12" name="gender" value="2">여자</button>
                    </div>
                </div>
            </div>
            <div class="col-5 box h-100">
                <h4 class="font-weight-bold mt-1">검색</h4>
                <div class="form-group row mb-1" id="autosearch">
                    <div class="col-9">
                        {{--<crawling_input_autocomplete :items= '{{ $all_tag_list }}' />--}}
                        {{--<search_autocomplete :items=  {!! json_encode($tag_list) !!} />--}}
                        <search_autocomplete :items=  '{{ $tag_list }}':pre_value='{{json_encode($params['search'])}}' />
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-primary col-md-12">검색</button>
                    </div>
                </div>
            </div>
        </form>

        <form method="POST" action="{{url('/data/crawling_batch/delete')}}">
            {{ csrf_field() }}
            <input type="hidden" id="board" name="board" value="{{$params['board']}}">
            <input type="hidden" id="type" name="type" value="{{$params['type']}}">
            <input type="hidden" name="search" value="{{$params['search']}}">
            <div>
                    @if($searchs->count())
                        <div class="text-center">
                            {!! $searchs->render() !!}
                        </div>
                    @endif

                <table class="table m-4  table-responsive-sm table-hover table-outline mb-0">
                    <thead  class="thead-dark">
                    <tr>
                        {{--<th scope="col"><input type="checkbox" name="check_all2" id="check_all2"></th>--}}
                        <th scope="col">플랫폼</th>
                        <th scope="col">분류</th>
                        <th scope="col">조건값</th>
                        <th scope="col">누적수집</th>
                        <th scope="col">누적게시수</th>
                        <th scope="col">전일수집</th>
                        <th scope="col">금일수집</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($searchs as $val)
                        <tr id="{{$val->id}}">
                            {{--<th scope="row"><input type="checkbox" name="check_item2[]" id="check_item2" value="{{$val->id}}"></th>--}}
                            <td>{{$val->board}}</td>
                            <td>{{$val->type}}</td>
                            <td>{{$val->search}}</td>
                            <td>{{$val->total}}</td>
                            <td>{{$val->open_count}}</td>
                            <td>{{$val->yesterday_count}}</td>
                            <td>{{$val->today_count}}</td>
                            <td>
                                @if($val->state==1)
                                    <button type='button' class="btn btn-danger" name="deactivate" value="{{$val->id}}">비할성화</button>
                                @elseif($val->state==3)
                                    <button type="button" class="btn btn-dark" disabled>오류 발생</button>
                                @else
                                    <button type="button" class="btn btn-outline-primary" name="activate" value="{{$val->id}}">활성화</button>
                                    <button type="button" class="btn btn-danger" name="delete" value="{{$val->id}}">삭제</button>
                                @endif
                            </td>
                            <td><a href="/admin/boards?type={{$params['board']}}&search={{$val->search}}&start_date=2014-07-20&gender={{$val->gender}}"><button type='button' class="btn btn-primary">해당 플랫폼 페이지로 이동</button></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-md-center invisible text-checking" id="text_check_loading"><img src="{{env('PUBLIC_PATH')}}/images/loading_spinner.gif" height="500" width="500"></div>
        </form>
    </main>
@endsection


@push('script')
    <script src="/js/app.js"></script>
    <script>
        var tag_list_input = new Vue({
            el:"#input",
            name:'input',
            data:{
                newTag:'',
                tags:[],
            },
            components:{
                crawling_input_autocomplete:Crawling_Autocomplete_input
            }
        });

        var tag_list_search = new Vue({
            el:'#autosearch',
            name:'search',
            data:{
                newTag:'',
            },
            components:{
                search_autocomplete: Autocomplete_search
            }
        });

        $(document).ready(function(){
            $("#input").focus();
        });

        function boardChange(sVal) {
            if(sVal=='instagram'){
                $('select[id="type"]').html("<option value='hashtag' {{($params['type']=='hashtag')? 'selected' : ''}} disabled>hashtag</option>" +
                                "<option value='account' {{($params['type']=='account')? 'selected' : ''}}>account</option>")
            }else{
                $('select[id="type"]').html("<option value='keyword' {{($params['type']=='keyword')? 'selected' : ''}}>keyword</option>" +
                    "<option value='channel' {{($params['type']=='channel')? 'selected' : ''}}>channel</option>");
            }
        }
        $('input[type="text"]').keydown(function() {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
        $('[name="crawling_execute"]').on('click',function () {
           $('#crawling_execute_form').submit();
        });
        $('[name="delete"]').on('click',function(e){
            var chk_array=Array();
            var id = e.target.value;
            chk_array[0]=e.target.value;
            $('.text-checking').removeClass('invisible');
            $('.text-checking').addClass('visible');
            $.ajax({
                url: "/admin/collect_batches/"+id,
                type: "DELETE",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'check_item': chk_array
                }
            })
                .done(function () {
                    window.location.reload()
                });
        });
        $('[name="deactivate"]').on('click',function(e){
            var chk_array=Array();
            var id = e.target.value;
            chk_array[0]=e.target.value;
            console.log(chk_array);
            $('.text-checking').removeClass('invisible');
            $('.text-checking').addClass('visible');
            $.ajax({
                url: "/admin/collect_batches/"+id,
                type: "put",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'state' : 0,
                    'check_item': chk_array
                }
            })
                .done(function (json) {
                    console.log(json);
                    window.location.reload()
                });
        });
        $('[name="activate"]').on('click',function(e){
            var chk_array=Array();
            var id = e.target.value;
            chk_array[0]=e.target.value;
            $('.text-checking').removeClass('invisible');
            $('.text-checking').addClass('visible');
            $.ajax({
                url: "/admin/collect_batches/"+id,
                type: "put",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'state' : 1,
                    'check_item': chk_array
                }
            })
                .done(function (response) {
                    var result = response.result;

                    if (result == 'fail') {
                        alert('등록에 실패하였습니다!')
                    }
                    window.location.reload()
                });
        });
        function loading(){
            $('.text-checking').removeClass('invisible');
            $('.text-checking').addClass('visible');
        }
    </script>
@endpush