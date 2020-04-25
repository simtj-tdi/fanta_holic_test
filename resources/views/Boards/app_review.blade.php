@extends('layouts.master')

@push('style')
    <style>
        .custom-control-label::before {
            left: 0.35rem;
            top: 0.35rem;
        }
        .custom-control-label::after {
            left: 0.35rem;
            top: 0.35rem;
        }
        #created {
            width: 100%;
            background:rgba(255,255,255,0.9);
            /*position: absolute;*/
            /*bottom: 0%;*/
            border-bottom:3px solid rgba(0,0,0,0.2);
            /*padding:10px;*/
            /*margin-top:11px;*/
            text-align: center;
        }
        .not-text-checked {
            background :black!important;
            color:white;
        }
        .text-exist {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .face-number{
            background: black;
            position:absolute;
            top:10%;
            left:0%;
        }
        .border-success {
            border: 5px solid #28a745!important;
        }
        .border-success #created {
            padding-bottom:5px;
            border-bottom: 5px solid #28a745!important;
        }
        .border-warning {
            border: 5px solid black !important;
        }
        .border-warning #created {
            padding-bottom:5px;
            border-bottom: 5px solid black!important;
        }

        #search_box {
            background: #cdcdcd;
            border: 1px solid #cfcfcf;
            border-radius: 6px;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            margin:19px 0px 22px 0px;
        }

        .box {
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
            display: inline-block;
            border:1px solid #cccccc;
            background: white;
        }

        a button {
            background: #7f7f7f;
            color: white;
        }

        label, input { display:block; }
        input.text { margin-bottom:12px; width:95%; padding: .4em; }
        fieldset { padding:10px; border:0; margin-top:25px;border-radius: 10px; }

        .ui-widget-header {
            border:none!important;
            background:white!important;
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

        /*2019.01.28 CCH 세로순배치 => 가로순배치*/
        .grid-item {
            width: 225px;
            margin-bottom: 10px;
        }
        .grid-item img { width: 100%}
        /*2019.01.28 CCH 세로순배치 => 가로순배치*/

        #A_tag div{
            padding: 0.2rem;
            background:#E6ECF2;
            color: #2680EB;
            font-size: 0.5rem;
        }
        .view {
            display:block!important;
            background: rgba(255,255,255,1);
        }
        @media ( max-width: 481px ) {
            #stv_list {
                /*right: 27%!important;*/
                width: auto!important;
            }
            #dialog-form,
            #modify_tag_form
            #individual_modify_tag_form {
                width: 35%!important;
            }
        }
    </style>
@endpush

@section('content')
    <main class="main">
        <form class="form-inline" name="search_form" id="search_form" method="GET" action="{{url('/admin/boards/bulk/app_review')}}">
            <input type="hidden" name="board_type" value="{{$params['board_type']}}">
            <input type="hidden" id="state" name="state" value="{{$params['state']}}">
            <input type="hidden" id="gender" name="gender" value="{{$params['gender']}}">
            <input type="hidden" id="start_date" name="start_date" value="{{$params['start_date']}}">
            <input type="hidden" id="end_date" name="end_date" value="{{$params['end_date']}}">
            <input type="hidden" id="app_review" name="app_review" value="{{$params['app_review']}}">
            <div id="search_box" class="row w-100">
                <div class="box form-group col-md-3">
                    <div class="row w-100">
                        <div class ='col mt-2 ml-3 mr-1 d-inline-block'>
                            <h6 class="font-weight-bold">연동날짜</h6>
                            <div>
                                <input type="text" class="form-control datetimepicker mt-2 d-inline-block mb-1" value="{{$params['start_date']}}" id="start_date" name="start_date" autocomplete="off" style="width: 7rem" onchange="submit()">
                                ~
                                <input type="text" class="form-control datetimepicker mt-2 d-inline-block mb-1" value="{{$params['end_date']}}" id="end_date" name="end_date" autocomplete="off" style="width: 7rem" onchange="submit()">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="box form-group col-md-2">
                    <h6 class="font-weight-bold">종류</h6>
                    <div  class="custom-radio row w-100">
                        <select class="form-control ml-3" id="board_type" name="board_type" value="{{$params['board_type']}}" onchange="submit()">
                            <option {{($params['board_type']==null)? 'selected':''}} value="">전체</option>
                            <option {{($params['board_type']=='instagram')? 'selected':''}} value="instagram">instagram</option>
                            <option {{($params['board_type']=='youtube')? 'selected':''}} value="youtube">youtube</option>
                            <option {{($params['board_type']=='news')? 'selected':''}} value="news">news</option>
                            <option {{($params['board_type']=='web')? 'selected':''}} value="web">web</option>
                        </select>
                    </div>
                </div>
                <div class="box form-group col-md-2">
                    <h6 class="font-weight-bold">게시물 상태</h6>
                    <div  class="custom-radio row w-100">
                        <button type="radio" class="btn {{($params['state']==null)? 'btn-primary':'btn-outline-primary'}} col ml-3 mr-1" name="state" value="">전체</button>
                        <button type="radio" class="btn {{($params['state']=='1')? 'btn-primary':'btn-outline-primary'}} col mx-1"  name="state" value="1" >게시</button>
                        <button type="radio" class="btn {{($params['state']=='0')? 'btn-primary':'btn-outline-primary'}} col mx-1"  name="state" value="0" >내림</button>
                    </div>
                </div>
                <div class="box form-group col-md-2">
                    <h6 class="font-weight-bold">성별</h6>
                    <div class="custom-radio row w-100">
                        <button type="radio" class="btn {{($params['gender']=='1') ? 'btn-primary':'btn-outline-primary'}} col ml-3 mr-1" name="gender" value="1">남자</button>
                        <button type="radio" class="btn {{($params['gender']=='2') ? 'btn-primary':'btn-outline-primary'}} col mx-1" name="gender" value="2">여자</button>
                    </div>
                </div>
                <div class="box form-group col-md-2">
                    <h6 class="font-weight-bold">검수</h6>
                    <div class="custom-radio row w-100">
                        <button type="radio" class="btn {{($params['app_review']=='0') ? 'btn-primary':'btn-outline-primary'}} col ml-3 mr-1" name="app_review" value="0">검수용 X</button>
                        <button type="radio" class="btn {{($params['app_review']=='1') ? 'btn-primary':'btn-outline-primary'}} col mx-1" name="app_review" value="1"> 검수용</button>
                    </div>
                </div>
                <div class="box form-group col-md-1">
                    <button type="submit" class="btn btn-primary mb-3" style="margin: 5px;">검색</button>
                </div>
            </div>
        </form>

        <div class="p-2" style="border:1px solid #CFCFCF">
            <h6>Total: {{$total}}</h6>
            <form name="form_review" id="form_review" method="post" action="{{url('/admin/boards/bulk/app_review')}}">
                {{ csrf_field() }}
                <input type="hidden" name="change_app_review">
                <input type="hidden" name="change_state">
                <input type="hidden" name="from_app_review">
                <input type="hidden" name="_method" value="put">
                <input type="hidden" name="board_type" value="{{$params['board_type']}}">
                <input type="hidden" name="app_review" value="{{$params['app_review']}}">
                <input type="hidden" id="state" name="state" value="{{$params['state']}}">
                <input type="hidden" id="gender" name="gender" value="{{$params['gender']}}">
                <input type="hidden" id="start_date" name="start_date" value="{{$params['start_date']}}">
                <input type="hidden" id="end_date" name="end_date" value="{{$params['end_date']}}">
                <div class="container-fluid">
                    <div class="row py-1">
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary mb-2" name="check_all" id="check_all" value=0>전체 선택</button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_open">게시</button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_close">내림</button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_review_on">검수용등록</button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_review_off">검수용등록해제</button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_text_check">text check</button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_face_check">face check</button>
                            <a href="/admin/boards/bulk/app_review?&start_date={{\Carbon\Carbon::now()->addDay(-3)->startOfDay()->toDateString()}}&end_date={{\Carbon\Carbon::now()->addDay(-3)->endOfDay()->toDateString()}}" style="float: right;margin:3px"><button type="button" class="btn btn-outline-primary">3일 전</button></a>
                            <a href="/admin/boards/bulk/app_review?&start_date={{\Carbon\Carbon::now()->addDay(-2)->startOfDay()->toDateString()}}&end_date={{\Carbon\Carbon::now()->addDay(-2)->endOfDay()->toDateString()}}" style="float: right;margin:3px"><button type="button" class="btn btn-outline-primary">2일 전</button></a>
                            <a href="/admin/boards/bulk/app_review?&start_date={{\Carbon\Carbon::now()->addDay(-1)->startOfDay()->toDateString()}}&end_date={{\Carbon\Carbon::now()->addDay(-1)->endOfDay()->toDateString()}}" style="float: right;margin:3px"><button type="button" class="btn btn-outline-primary">1일 전</button></a>
                            <a href="/admin/boards/bulk/app_review?&start_date={{\Carbon\Carbon::now()->startOfDay()->toDateString()}}&end_date={{\Carbon\Carbon::now()->endOfDay()->toDateString()}}" style="float: right;margin:3px"><button type="button" class="btn btn-outline-primary">today</button></a>
                        </div>
                    </div>
                </div>
                <hr>
                @if($rows->count())
                    <div class="text-center">
                        {!! $rows->render() !!}
                    </div>
                @endif
                {{--사이드 컨트롤 메뉴--}}
                <div class="bg-white float-right position-fixed p-1" id="stv_list" style="z-index:400;border:1px solid black ;right:17px; width:6%">
                    <button type="button" class="btn btn-outline-primary mb-2" name="check_all" id="check_all" value=0>전체 선택</button>
                    <button type="button" class="btn btn-outline-primary mb-2" name="btn_open">게시</button>
                    <button type="button" class="btn btn-outline-primary mb-2" name="btn_close">내림</button>
                    <button type="button" class="btn btn-outline-primary mb-2" name="btn_review_on">검수용등록</button>
                    <button type="button" class="btn btn-outline-primary mb-2" name="btn_review_off">검수용등록해제</button>
                </div>

                <div id = "columns" class="grid" style="height: 100%">
                    @foreach($rows as $val)
                        <div class = "grid-item {{($val->state==1)? 'border-success' : 'border-warning'}} custom-checkbox">
                            <input type = "checkbox" name = "check_item[]" id = "{{$val->id}}" value = "{{$val->id}}" class = "custom-control-input">
                            <label class="custom-control-label " for="{{$val->id}}">
                                <div id = "created" class="font-weight-bold  {{($val->text_check==0)? 'not-text-checked' : '' }}" style="{{($val->text_check==2) ? 'background:red':''}}">
                                                            {{$val->created_at}}
                                </div>
                                <img  src="{{env('CDN_URL').$val->thumbnail_url}}" value="{{$val->id}}">
    {{--                            @if($val->face_check != null)--}}
                                    <div class="face-number"><h4 style="color: yellow;">{{$val->face_check}}</h4></div>
                                {{--@endif--}}
                                @if($val->text_check==2)
                                    <div class="text-exist"><h1 style="color: red;">TEXT</h1></div>
                                @endif
                            </label>

                        </div>
                    @endforeach
                </div>
                @if($rows->count())
                    <div class="text-center">
                        {!! $rows->render() !!}
                    </div>
                @endif
                {{--<div class="row justify-content-md-center invisible text-checking" id="text_check_loading"><img src="{{env('PUBLIC_PATH')}}/images/loading_spinner.gif" height="500" width="500"></div>--}}
            </form>
        </div>
        <div class="row justify-content-md-center invisible position-fixed" style="top: 40%;right: 40%" id="ajax_loading"><img src="{{env('PUBLIC_PATH')}}/images/loading_spinner.gif" height="250" width="250"></div>
    </main>
@endsection

@push('script')
    <script src="/js/app.js"></script>
    <script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/boardcontrol.js"></script>
    <script>
        var token="{{csrf_token()}}";
        $(function(){
            $("[name='check_all']").click(function(){

                if($("[name='check_all']").val()==0){
                    $("[name='check_all']").val(1);
                    $("[name='check_item[]']").prop("checked",true);
                }else{
                    $("[name='check_all']").val(0);
                    $("[name='check_item[]']").prop("checked",false);
                }
            });

            //2019.01.25 cch alert창 form 수정
            $("[name='btn_open']").on('click',function(e){
                e.preventDefault();
                var chk_len = $('[name="check_item[]"]:checked').length;
                if (chk_len > 0) {
                    $('[name="change_state"]').val(1);
                    $('[name="from_app_review"]').val(true);

                    $('#form_review').attr('action',"/admin/boards/bulk/open").submit();
                } else {
                    alert('게시물을 하나 이상 선택해 주세요');
                    return false;
                }
            });

            $("[name='btn_close']").on("click",function(e){
                e.preventDefault();
                var chk_len = $('[name="check_item[]"]:checked').length;
                if (chk_len > 0) {
                    $('[name="change_state"]').val(0);
                    $('[name="from_app_review"]').val(true);
                    $('#form_review').attr('action',"/admin/boards/bulk/open").submit();
                } else {
                    alert('게시물을 하나 이상 선택해 주세요');
                    return false;
                }
            });

            $('[name="btn_review_on"]').on('click',function(e){
                e.preventDefault();
                var chk_len = $('[name="check_item[]"]:checked').length;
                if (chk_len > 0) {
                    $('[name="change_app_review"]').val(1);
                    $('#form_review').attr('action',"/admin/boards/bulk/app_review").submit();
                } else {
                    alert('게시물을 하나 이상 선택해 주세요');
                    return false;
                }
            });

            $('[name="btn_review_off"]').on('click',function(e){
                e.preventDefault();
                var chk_len = $('[name="check_item[]"]:checked').length;
                if (chk_len > 0) {
                    $('[name="change_app_review"]').val(0);
                    $('#form_review').attr('action',"/admin/boards/bulk/app_review").submit();
                } else {
                    alert('게시물을 하나 이상 선택해 주세요');
                    return false;
                }
            });

            $("[name='btn_face_check']").on('click',function (e) {
                e.preventDefault();
                $('#ajax_loading').removeClass('invisible');
                $('#ajax_loading').addClass('visible');
                var chk_len = $('[name="check_item[]"]:checked').length;
                if (chk_len > 0) {
                    $('[name="_method"]').val('put');
                    $('#form_review').attr('action', "/admin/boards/bulk/face").submit();
                } else {
                    $('#ajax_loading').removeClass('visible');
                    $('#ajax_loading').addClass('invisible');
                    alert('게시물을 하나 이상 선택해 주세요');
                    return false;
                }
            });

            $("[name='btn_text_check']").on('click',function (e) {
                e.preventDefault();
                $('#ajax_loading').removeClass('invisible');
                $('#ajax_loading').addClass('visible');
                var chk_len = $('[name="check_item[]"]:checked').length;
                if (chk_len > 0) {
                    $('[name="_method"]').val('put');
                    $('#form_review').attr('action', "/admin/boards/bulk/text").submit();
                } else {
                    $('#ajax_loading').removeClass('visible');
                    $('#ajax_loading').addClass('invisible');
                    alert('게시물을 하나 이상 선택해 주세요');
                    return false;
                }
            });



        })
    </script>
@endpush