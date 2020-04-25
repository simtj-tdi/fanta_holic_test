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
            background: rgba(255, 255, 255, 0.9);
            /*position: absolute;*/
            /*bottom: 0%;*/
            border-bottom: 3px solid rgba(0, 0, 0, 0.2);
            /*padding:10px;*/
            /*margin-top:11px;*/
            text-align: center;
        }

        .not-text-checked {
            background: black !important;
            color: white;
        }

        .text-exist {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .text-checking {
            z-index: 900;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .border-success {
            border: 5px solid #28a745 !important;
        }

        .border-success #created {
            padding-bottom: 5px;
            border-bottom: 5px solid #28a745 !important;
        }

        .border-warning {
            border: 5px solid black !important;
        }

        .border-warning #created {
            padding-bottom: 5px;
            border-bottom: 5px solid black !important;
        }

        #search_box {
            background: #cdcdcd;
            border: 1px solid #cfcfcf;
            border-radius: 6px;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            margin: 19px 0px 22px 0px;
        }

        .box {
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
            display: inline-block;
            border: 1px solid #cccccc;
            background: white;
        }

        a button {
            background: #7f7f7f;
            color: white;
        }

        label, input {
            display: block;
        }

        input.text {
            margin-bottom: 12px;
            width: 95%;
            padding: .4em;
        }

        fieldset {
            padding: 10px;
            border: 0;
            margin-top: 25px;
            border-radius: 10px;
        }

        .ui-widget-header {
            border: none !important;
            background: white !important;
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

        .face-number {
            background: black;
            position: absolute;
            top: 10%;
            left: 0%;
        }

        /*2019.01.28 CCH 세로순배치 => 가로순배치*/
        .grid-item {
            width: 225px;
            margin-bottom: 10px;
        }

        .grid-item img {
            width: 100%
        }

        /*2019.01.28 CCH 세로순배치 => 가로순배치*/
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: rgba(255, 255, 255, 0.7);
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        #A_tag div {
            padding: 0.2rem;
            background: #E6ECF2;
            color: #2680EB;
            font-size: 0.5rem;
        }

        .view {
            display: block !important;
            background: rgba(255, 255, 255, 1);
        }

        #mobile_search_box {
            display: none;
        }

        @media ( max-width: 481px ) {
            #search_box,
            #menu_bar {
                display: none !important;
            }

            #mobile_search_box {
                display: block;
            }

            #stv_list {
                /*right: 27%!important;*/
                width: auto !important;
            }

            .grid-item {
                width: 100%;
            }

            #dialog-form,
            #modify_tag_form
            #individual_modify_tag_form {
                width: 35% !important;
            }
        }
    </style>
@endpush

@section('content')
    <main class="main">
        <form class="form-inline" name="search_form" id="search_form" method="GET"
              action="{{ route('board.index') }}">
            <input type="hidden" id="tags" name="tags" value="{{$params['tags']}}">

            <div id="mobile_search_box" class=" w-100 mt-0 mb-0 ml-3">
                <div class="row box ">
                    <h6 class="font-weight-bold ml-5 mt-2">연동날짜</h6>
                    <div class="mb-1">
                        <input type="text" class="form-control datetimepicker ml-5 d-inline-block"
                               value="{{$params['start_date']}}" id="start_date" name="start_date" autocomplete="off"
                               style="width:35% " onchange="submit()">
                        ~
                        <input type="text" class="form-control datetimepicker  d-inline-block"
                               value="{{$params['end_date']}}" id="end_date" name="end_date" autocomplete="off"
                               style="width: 35%" onchange="submit()">
                    </div>
                </div>

                <div class="row w-100">
                    <div class="box col">
                        <h6 class="font-weight-bold ml-3 mt-2">종류</h6>
                        <div class="custom-radio">
                            <select class="form-control ml-1" id="type" name="type" value="{{$params['type']}}"
                                    onchange="submit()">
                                <option {{($params['type']==null)? 'selected':''}} value="">전체</option>
                                <option {{($params['type']=='instagram')? 'selected':''}} value="instagram">
                                    instagram
                                </option>
                                <option {{($params['type']=='youtube')? 'selected':''}} value="youtube">youtube
                                </option>
                                <option {{($params['type']=='news')? 'selected':''}} value="news">news</option>
                                {{--                                    <option {{($params['type']=='web')? 'selected':''}} value="web">web</option>--}}
                                <option {{($params['type']=='vlive')? 'selected':''}} value="vlive">vlive</option>
                                <option {{($params['type']=='twitter')? 'selected':''}} value="twitter">twitter
                                </option>
                                <option {{($params['type']=='myfeed')? 'selected':''}} value="myfeed">myfeed
                                </option>
                                <option {{($params['type']=='event')? 'selected':''}} value="event">event</option>
                                <option {{($params['type']=='fanfeed')? 'selected':''}} value="fanfeed">fanfeed
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="box col">
                        <h6 class="font-weight-bold mt-2">게시물 상태</h6>
                        <div class="custom-radio row w-100 ml-2">
                            <select class="form-control ml-1" id="state" name="state" value="{{$params['state']}}"
                                    onchange="submit()">
                                <option {{($params['state']==null)? 'selected':''}} value="">전체</option>
                                <option {{($params['state']=='2')? 'selected':''}} value="2">비게시</option>
                                <option {{($params['state']=='1')? 'selected':''}} value="1">게시</option>
                                <option {{($params['state']=='0')? 'selected':''}} value="0">미검수</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="box col p-0">
                        <h6 class="font-weight-bold mt-2">구글 검수</h6>
                        {{--<div  class="custom-radio row w-100">--}}
                        <select class="form-control ml-1" id="app_review" name="app_review"
                                value="{{$params['app_review']}}" onchange="submit()">
                            <option {{($params['app_review']==null)? 'selected':''}} value="">전체</option>
                            <option {{($params['app_review']=='1')? 'selected':''}} value="1">검수용</option>
                            <option {{($params['app_review']=='0')? 'selected':''}} value='0'>검수용X</option>
                        </select>
                    </div>
                </div>
                {{--//////////////////////--}}
                {{--<div class="w-100"></div>--}}
                {{--//////////////////////--}}
                <div class="row">
                    <div class="box col">
                        <h6 class="font-weight-bold ml-5 mt-2">얼굴</h6>
                        <div class="custom-radio row ml-2 w-100">
                            <select class="form-control ml-1" id="face_check" name="face_check"
                                    value="{{$params['face_check']}}" onchange="submit()">
                                <option {{($params['face_check']=='all')? 'selected':''}} value='all'>전체</option>
                                <option {{($params['face_check']==null)? 'selected':''}} value=''>미검수</option>
                                <option {{($params['face_check']=='0')? 'selected':''}} value="0">없음</option>
                                <option {{($params['face_check']=='1')? 'selected':''}} value="1">있음</option>
                            </select>
                        </div>
                    </div>
                    <div class="box col">
                        <h6 class="font-weight-bold mt-2">텍스트</h6>
                        <div class="custom-radio row w-100">
                            <select class="form-control ml-1" id="text_check" name="text_check"
                                    value="{{$params['text_check']}}" onchange="submit()">
                                <option {{($params['text_check']=='')? 'selected':''}} value="">전체</option>
                                <option {{($params['text_check']=='0')? 'selected':''}} value="0">미검수</option>
                                <option {{($params['text_check']=='1')? 'selected':''}} value="1">없음</option>
                                <option {{($params['text_check']=='2')? 'selected':''}} value="2">있음</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row w-100 box">
                    <h6 class="font-weight-bold  mt-2">태그검색</h6>
                    <div id="search" class="d-inline-block w-100">
                        <search_autocomplete :items='{{ $tag_list }}' :pre_value='{{json_encode($params['tags'])}}'/>
                    </div>
                </div>
                <div class="row w-100 box">
                    <h6 class="font-weight-bold ml-2 mt-2">키워드검색</h6>

                    <div id="s_search" class="d-inline-block" style="width:70%">
                        <input class="form-control" type='text' name='' value="{{$params['search']}}">
                    </div>
                    <button type="submit" class="d-inline-block btn btn-primary mx-0 mb-3 mr-5"
                            style="float:right;margin: 5px;">검색
                    </button>
                </div>
            </div>
            <div id="search_box" class=" w-100 mt-0 mb-0">
                <div class="row">
                    <div class='box col-3 px-0 d-inline-block'>
                        <h6 class="font-weight-bold ml-5 mt-2">연동날짜</h6>
                        <div class="mb-1">
                            <input type="text" class="form-control datetimepicker ml-5 d-inline-block"
                                   value="{{$params['start_date']}}" id="start_date" name="start_date"
                                   autocomplete="off" style="width:35% " onchange="submit()">
                            ~
                            <input type="text" class="form-control datetimepicker  d-inline-block"
                                   value="{{$params['end_date']}}" id="end_date" name="end_date" autocomplete="off"
                                   style="width: 35%" onchange="submit()">
                        </div>
                    </div>
                    <div class="box col-4">
                        <h6 class="font-weight-bold mt-2">매체</h6>
                        <div class="custom-radio row w-100">
                            @if(Auth::user()!= null && (Auth::user()->app == 'jihoon' || Auth::user()->app == 'krieshachu'))
                                <select class="form-control ml-1" id="type" name="type" value="{{$params['type']}}"
                                        onchange="submit()">
                                    <option {{($params['type']==null)? 'selected':''}} value="">전체</option>
                                    <option {{($params['type']=='instagram')? 'selected':''}} value="instagram">
                                        인스타그램
                                    </option>
                                    <option {{($params['type']=='youtube')? 'selected':''}} value="youtube">유튜브
                                    </option>
                                    <option {{($params['type']=='news')? 'selected':''}} value="news">뉴스</option>
                                    {{--                                    <option {{($params['type']=='web')? 'selected':''}} value="web">web</option>--}}
                                    <option {{($params['type']=='vlive')? 'selected':''}} value="vlive">VLIVE</option>
                                    <option {{($params['type']=='twitter')? 'selected':''}} value="twitter">트위터
                                    </option>
                                    <option {{($params['type']=='myfeed')? 'selected':''}} value="myfeed">MyFeed
                                    </option>
                                    <option {{($params['type']=='event')? 'selected':''}} value="event">Event</option>
                                    <option {{($params['type']=='fanfeed')? 'selected':''}} value="fanfeed">FanFeed
                                    </option>
                                </select>
                                {{--<select class="form-control" name ='type'></select>--}}
                                {{--<button type="radio" class="btn {{($params['type']=='twitter')? 'btn-primary':'btn-outline-primary'}} col mx-1"  name="type" value="twitter" >twitter</button>--}}
                                {{--<button type="radio" class="btn {{($params['type']=='vlive')? 'btn-primary':'btn-outline-primary'}} col mx-1"  name="type" value="vlive" >vlive</button>--}}
                                {{--<button type="radio" class="btn {{($params['type']=='myfeed')? 'btn-primary':'btn-outline-primary'}} col mx-1"  name="type" value="myfeed" >myfeed</button>--}}
                                {{--<button type="radio" class="btn {{($params['type']=='event')? 'btn-primary':'btn-outline-primary'}} col mx-1"  name="type" value="event" >event</button>--}}
                            @else
                                <button type="radio"
                                        class="btn {{($params['type']==null)? 'btn-primary':'btn-outline-primary'}} col ml-3 mr-1"
                                        name="type" value="">전체
                                </button>
                                <button type="radio"
                                        class="btn {{($params['type']=='instagram')? 'btn-primary':'btn-outline-primary'}} col mx-1"
                                        name="type" value="instagram">instagram
                                </button>
                                <button type="radio"
                                        class="btn {{($params['type']=='youtube')? 'btn-primary':'btn-outline-primary'}} col mx-1"
                                        name="type" value="youtube">youtube
                                </button>
                                <button type="radio"
                                        class="btn {{($params['type']=='news')? 'btn-primary':'btn-outline-primary'}} col mx-1"
                                        name="type" value="news">news
                                </button>
                                <button type="radio"
                                        class="btn {{($params['type']=='web')? 'btn-primary':'btn-outline-primary'}} col mx-1"
                                        name="type" value="web">web
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="box col">
                        <h6 class="font-weight-bold mt-2">게시물 상태</h6>
                        <div class="custom-radio row w-100 ml-2">
                            {{--<select class="form-control ml-1" id="state" name="state" value="{{$params['state']}}" onchange="submit()">--}}
                            {{--<option {{($params['state']==null)? 'selected':''}} value="">전체</option>--}}
                            {{--<option {{($params['state']=='1')? 'selected':''}} value="1">게시</option>--}}
                            {{--<option {{($params['state']=='0')? 'selected':''}} value="0">내림</option>--}}
                            {{--</select>--}}
                            <div class="custom-radio row w-100">
                                <button type="radio"
                                        class="btn {{($params['state']==null)? 'btn-primary':'btn-outline-primary'}} col-3 text-center"
                                        name="state" value="">전체
                                </button>
                                <button type="radio"
                                        class="btn {{($params['state']=='2')? 'btn-primary':'btn-outline-primary'}} col-3 text-center"
                                        name="state" value="2">비게시
                                </button>
                                <button type="radio"
                                        class="btn {{($params['state']=='1')? 'btn-primary':'btn-outline-primary'}} col-3 text-center"
                                        name="state" value="1">게시
                                </button>
                                <button type="radio"
                                        class="btn {{($params['state']=='0')? 'btn-primary':'btn-outline-primary'}} col-3 text-center"
                                        name="state" value="0">미검수
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="box col p-0">
                        <h6 class="font-weight-bold mt-2">구글 검수</h6>
                        {{--<div  class="custom-radio row w-100">--}}
                        {{--<select class="form-control ml-1" id="app_review" name="app_review" value="{{$params['app_review']}}" onchange="submit()">--}}
                        {{--<option {{($params['app_review']==null)? 'selected':''}} value="">전체</option>--}}
                        {{--<option {{($params['app_review']=='1')? 'selected':''}} value="1">검수용</option>--}}
                        {{--<option {{($params['app_review']=='0')? 'selected':''}} value='0'>검수용X</option>--}}
                        {{--</select>--}}
                        {{--</div>--}}
                        <div class="custom-radio row w-100 mx-0">
                            <button type="radio"
                                    class="btn {{($params['app_review']==null) ? 'btn-primary':'btn-outline-primary'}} col ml-1 mr-1"
                                    name="app_review" value="">전체
                            </button>
                            <button type="radio"
                                    class="btn {{($params['app_review']=='1') ? 'btn-primary':'btn-outline-primary'}} col  mr-1"
                                    name="app_review" value="1">검수용
                            </button>
                            <button type="radio"
                                    class="btn {{($params['app_review']=='0') ? 'btn-primary':'btn-outline-primary'}} col "
                                    name="app_review" value="0">검수용X
                            </button>
                        </div>
                    </div>

                </div>
                {{--//////////////////////--}}
                {{--<div class="w-100"></div>--}}
                {{--//////////////////////--}}
                <div class="row">
                    <div class="box col">
                        <h6 class="font-weight-bold ml-5 mt-2">얼굴</h6>
                        <div class="custom-radio row ml-2 w-100">
                            {{--<select class="form-control ml-1" id="face_check" name="face_check" value="{{$params['face_check']}}" onchange="submit()">--}}
                            {{--<option {{($params['face_check']=='all')? 'selected':''}} value='all'>전체</option>--}}
                            {{--<option {{($params['face_check']==null)? 'selected':''}} value=''>미검수</option>--}}
                            {{--<option {{($params['face_check']=='0')? 'selected':''}} value="0">없음</option>--}}
                            {{--<option {{($params['face_check']=='1')? 'selected':''}} value="1">있음</option>--}}
                            {{--</select>--}}
                            <button type="radio"
                                    class="btn {{($params['face_check']=='all') ? 'btn-primary':'btn-outline-primary'}} col ml-3 mr-1"
                                    name="face_check" value="all">전체
                            </button>
                            <button type="radio"
                                    class="btn {{($params['face_check']==null) ? 'btn-primary':'btn-outline-primary'}} col  mr-1"
                                    name="face_check" value="">미검수
                            </button>
                            <button type="radio"
                                    class="btn {{($params['face_check']=='0') ? 'btn-primary':'btn-outline-primary'}} col mr-1"
                                    name="face_check" value="0">없음
                            </button>
                            <button type="radio"
                                    class="btn {{($params['face_check']=='1') ? 'btn-primary':'btn-outline-primary'}} col "
                                    name="face_check" value="1">있음
                            </button>
                        </div>
                    </div>
                    <div class="box col">
                        <h6 class="font-weight-bold mt-2">텍스트</h6>
                        <div class="custom-radio row w-100">
                            {{--<select class="form-control ml-1" id="text_check" name="text_check" value="{{$params['text_check']}}" onchange="submit()">--}}
                            {{--<option {{($params['text_check']=='3')? 'selected':''}} value="3">전체</option>--}}
                            {{--<option {{($params['text_check']=='0')? 'selected':''}} value="0">미검수</option>--}}
                            {{--<option {{($params['text_check']=='1')? 'selected':''}} value="1">없음</option>--}}
                            {{--<option {{($params['text_check']=='2')? 'selected':''}} value="2">있음</option>--}}
                            {{--</select>--}}
                            <button type="radio"
                                    class="btn {{($params['text_check']=='3') ? 'btn-primary':'btn-outline-primary'}} col ml-2 mr-1"
                                    name="text_check" value="3">전체
                            </button>
                            <button type="radio"
                                    class="btn {{($params['text_check']=='0') ? 'btn-primary':'btn-outline-primary'}} col mr-1"
                                    name="text_check" value="0">미검수
                            </button>
                            <button type="radio"
                                    class="btn {{($params['text_check']=='1') ? 'btn-primary':'btn-outline-primary'}} col mr-1 "
                                    name="text_check" value="1">없음
                            </button>
                            <button type="radio"
                                    class="btn {{($params['text_check']=='2') ? 'btn-primary':'btn-outline-primary'}} col  "
                                    name="text_check" value="2">있음
                            </button>
                        </div>
                    </div>
                    <div class=' box col-3  d-inline-block'>
                        <h6 class="font-weight-bold  mt-2">태그검색</h6>
                        <div id="search" class="d-inline-block w-100">
                            {{--                                <search_autocomplete :items= {!! json_encode($tag_list) !!}/>--}}
                            <search_autocomplete :items='{{ $tag_list }}'
                                                 :pre_value='{{json_encode($params['tags'])}}'/>
                        </div>
                    </div>
                    <div class='box col-4  px-0 d-inline-block'>
                        <h6 class="font-weight-bold ml-2 mt-2">키워드검색</h6>

                        <div id="s_search" class="d-inline-block" style="width:70%">
                            {{--                                <search_autocomplete :items= {!! json_encode($tag_list) !!}/>--}}
                            <input class="form-control" type='text' name='' value="{{$params['search']}}">
                            {{--<s_search_autocomplete :items= '{{ $search_list }}':pre_value='{{json_encode($params['search'])}}'/>--}}
                        </div>
                        {{--<div  class="custom-radio d-inline-block">--}}
                        {{--<input class="form-control  mb-3" type="text" name="search" value="{{$params['search']}}">--}}
                        {{--</div>--}}
                        <button type="submit" class="d-inline-block btn btn-primary mx-0 mb-3 mr-5"
                                style="float:right;margin: 5px;">검색
                        </button>
                    </div>
                </div>
            </div>
        </form>


        <div class="p-2" style="border:1px solid #CFCFCF">
            <h6>Total: {{$total}}</h6>
            <form name="form_{{$params['type']}}" id="form_{{$params['type']}}" method="post"
                  action="{{url('/admin/boards/bulk')}}">
                {{ csrf_field() }}
                <input type="hidden" name="Inspection">
                <input type="hidden" name="change_state">
                <input type="hidden" name="from_app_review">
                <input type="hidden" name="change_state">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name='app_review' value='0'>
                <input type="hidden" name="type" value="{{$params['type']}}">
                <input type="hidden" id="state" name="state" value="{{$params['state']}}">
                <input type="hidden" id="start_date" name="start_date" value="{{$params['start_date']}}">
                <input type="hidden" id="end_date" name="end_date" value="{{$params['end_date']}}">
                <input type="hidden" id="text_check" name="text_check" value="{{$params['text_check']}}">
                <input type="hidden" id="send_tag" name="send_tag">
                <input type="hidden" id="common_tags" name="common_tags">
                <input type="hidden" id="individual_modify_tag_id">
                <div class="container-fluid">
                    <div class="row py-1">
                        <div class="col">

                            {{--<span type='button' name="alldrop" style="width:2rem;height: 2rem;" class="icon-options-vertical" ></span>--}}
                            <button type="button" name="alldrop"
                                    class="icon-options-vertical btn btn-primary mb-2"></button>
                            <button type="button" class="btn btn-primary mb-2" name="check_all" id="check_all" value=0>
                                전체 선택
                            </button>
                            <button type="button" class="btn btn-primary mb-2" name="btn_move_to_man">남자로 이동</button>
                            <button type="button" class="btn btn-primary mb-2" name="btn_move_to_woman">여자로 이동</button>
                            <button type="button" class="btn btn-primary mb-2" name="btn_open">게시</button>
                            <button type="button" class="btn btn-primary mb-2" name="btn_close">내림</button>
                            <button type="button" class="btn btn-primary mb-2" name="btn_modify_tag">#(Tag)수정</button>
                            @if(in_array($params['type'],['news','web','myfeed','event']))
                                <button type="button" class="btn btn-info  mb-2" id="btn_create">등록</button>
                            @endif
                            <button type="button" class="btn btn-primary mb-2" name="btn_text_check">TextCheck</button>
                            <button type="button" class="btn btn-primary mb-2" name="btn_face_check">Face check</button>
                            <button type="button" class="btn btn-primary mb-2" name="btn_review_on">검수용등록</button>
                            <button type="button" class="btn btn-primary mb-2" name="btn_review_off">검수용등록해제</button>
                            <button type="submit" class="btn btn-danger mb-2">삭제</button>

                            <button type="button" class="btn btn-primary" style="float: right;margin:3px"
                                    name="threedaysago">3일 전
                            </button>
                            <button type="button" class="btn btn-primary" style="float: right;margin:3px"
                                    name="twodaysago">2일 전
                            </button>
                            <button type="button" class="btn btn-primary" style="float: right;margin:3px"
                                    name="onedaysago">1일 전
                            </button>
                            <button type="button" class="btn btn-primary" style="float: right;margin:3px" name="today">
                                today
                            </button>
                        </div>
                    </div>
                </div>
                <div class="container-fluid position-fixed invisible"
                     style="z-index:400;border:1px solid black ;background: white;width: 90%;" id="menu_bar">
                    <div class="row py-1">
                        <div class="col">
                            <button type="button" name="alldrop"
                                    class="icon-options-vertical btn btn-primary mb-2"></button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="check_all" id="check_all"
                                    value=0>전체 선택
                            </button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_move_to_man">남자로 이동
                            </button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_move_to_woman">여자로 이동
                            </button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_open">게시</button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_close">내림</button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_modify_tag">#(Tag)수정
                            </button>
                            @if(in_array($params['type'],['news','web']))
                                <button type="button" class="btn btn-outline-pimary  mb-2" id="btn_create">등록</button>
                            @endif
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_text_check">TextCheck
                            </button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_face_check">Face
                                check
                            </button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_review_on">검수용등록
                            </button>
                            <button type="button" class="btn btn-outline-primary mb-2" name="btn_review_off">검수용등록해제
                            </button>

                            <button type="submit" class="btn btn-danger mb-2">삭제</button>

                            <button type="button" class="btn btn-primary" style="float: right;margin:3px"
                                    name="threedaysago">3일 전
                            </button>
                            <button type="button" class="btn btn-primary" style="float: right;margin:3px"
                                    name="twodaysago">2일 전
                            </button>
                            <button type="button" class="btn btn-primary" style="float: right;margin:3px"
                                    name="onedaysago">1일 전
                            </button>
                            <button type="button" class="btn btn-primary" style="float: right;margin:3px" name="today">
                                today
                            </button>
                            {{--<a href="/admin/boards?type={{$params['type']}}&start_date={{\Carbon\Carbon::now()->addDay(-3)->startOfDay()->toDateString()}}&end_date={{\Carbon\Carbon::now()->addDay(-3)->endOfDay()->toDateString()}}" style="float: right;margin:3px"><button type="button" class="btn btn-outline-primary">3일 전</button></a>--}}
                            {{--<a href="/admin/boards?type={{$params['type']}}&start_date={{\Carbon\Carbon::now()->addDay(-2)->startOfDay()->toDateString()}}&end_date={{\Carbon\Carbon::now()->addDay(-2)->endOfDay()->toDateString()}}" style="float: right;margin:3px"><button type="button" class="btn btn-outline-primary">2일 전</button></a>--}}
                            {{--<a href="/admin/boards?type={{$params['type']}}&start_date={{\Carbon\Carbon::now()->addDay(-1)->startOfDay()->toDateString()}}&end_date={{\Carbon\Carbon::now()->addDay(-1)->endOfDay()->toDateString()}}" style="float: right;margin:3px"><button type="button" class="btn btn-outline-primary">1일 전</button></a>--}}
                            {{--<a href="/admin/boards?type={{$params['type']}}&start_date={{\Carbon\Carbon::now()->startOfDay()->toDateString()}}&end_date={{\Carbon\Carbon::now()->endOfDay()->toDateString()}}" style="float: right;margin:3px"><button type="button" class="btn btn-outline-primary">today</button></a>--}}
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
                {{--<div class="bg-white float-right position-fixed p-1" id="stv_list" style="z-index:400;border:1px solid black ;right:17px; width:6%">--}}
                {{--<span type='button' name="alldrop" style="width:2rem;height: 2rem;" data-feather="settings" ></span>--}}
                {{--<button type="button" class="btn btn-outline-primary mb-2" name="check_all" id="check_all" value=0>전체 선택</button>--}}
                {{--<button type="button" class="btn btn-outline-primary mb-2" name="btn_move_to_man">남자로 이동</button>--}}
                {{--<button type="button" class="btn btn-outline-primary mb-2" name="btn_move_to_woman">여자로 이동</button>--}}
                {{--<button type="button" class="btn btn-outline-primary mb-2" name="btn_open" >게시</button>--}}
                {{--<button type="button" class="btn btn-outline-primary mb-2" name="btn_close" >내림</button>--}}
                {{--<button type="button" class="btn btn-outline-primary mb-2" name="btn_modify_tag">#(Tag)수정</button>--}}
                {{--<button type="button" class="btn btn-outline-primary mb-2" name="btn_text_check">TextCheck</button>--}}
                {{--<button type="submit" class="btn btn-danger mb-2">삭제</button>--}}
                {{--</div>--}}

                <div id="columns" class="grid" style="height: 100%">
                    @foreach($rows as $val)
                        <div class="grid-item {{($val->state==1)? 'border-success' : 'border-warning'}} custom-checkbox card">
                            <div class="dropdown position-absolute" style="z-index: 10; right: 0rem;"
                                 id="{{$val->id}}_dropdown">
                                <span type='button' class="dropbtn icon-options-vertical btn-primary p-1 pt-2"
                                      id="{{$val->id}}_dropbtn"></span>
                                <div class="dropdown-content p-2 1"
                                     style="width: 215px; right: 0rem; border-bottom:1px solid black"
                                     id="{{$val->id}}_dropdown-content">
                                    <div>
                                        <div class="row w-100 ml-0">
                                            <div class="col-2 p-0"><span data-feather="tag"></span>
                                                <p>태그</p></div>
                                            <div id='A_tag' class="col-10 p-0">
                                                <p class="mb-1">A태그</p>
                                                @if(isset($val->ori_tag[0]) && $val->ori_tag[0] != null)
                                                    <div class="d-inline-block">{{$val->ori_tag[0]}}</div>
                                                    @if(isset($val->ori_tag[1]) && $val->ori_tag[1] != null)
                                                        <div class="d-inline-block">{{$val->ori_tag[1]}}</div>
                                                    @endif
                                                    ...
                                                    <div class="d-inline-block float-right">{{count($val->ori_tag)}}
                                                        개
                                                    </div>
                                                @else
                                                    ...
                                                    <div class="d-inline-block float-right">0개</div>
                                                @endif
                                                <hr class="mb-1 mt-1">
                                                <p class="mb-1">B태그</p>
                                                @if(isset($val->custom_tag[0]) && $val->custom_tag[0] != null)
                                                    <div class="d-inline-block">{{$val->custom_tag[0]}}</div>
                                                    ...
                                                    <button type='button' class='float-right'
                                                            name="individual_modify_tag" value="{{$val->id}}"
                                                            style="font-size: 0.5rem;">수정
                                                    </button>
                                                    <div class="d-inline-block float-right">{{count($val->custom_tag)}}
                                                        개
                                                    </div>
                                                @else
                                                    ...
                                                    <button type='button' class='float-right'
                                                            name="individual_modify_tag" value="{{$val->id}}"
                                                            style="font-size: 0.5rem;">수정
                                                    </button>
                                                    <div class="d-inline-block float-right">0개</div>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="row w-100 ml-0">
                                            <div class="col-2 p-0">text</div>
                                            <div class="col-10 pr-0">
                                                <div class="row" id="{{$val->id}}_check">
                                                    <button type="button"
                                                            class='col-3 p-1 m-1 {{($val->text_check=='0') ? 'btn-primary':'btn-outline-primary'}}'
                                                            style="font-size:0.5rem" name="not_checked" disabled>미검수
                                                    </button>
                                                    <button type="button"
                                                            class='col-3 p-1 m-1 {{($val->text_check=='1') ? 'btn-primary':'btn-outline-primary'}}'
                                                            style="font-size:0.5rem" name="individual_not"
                                                            value="{{$val->id}}">없음
                                                    </button>
                                                    <button type="button"
                                                            class='col-3 p-1 m-1 {{($val->text_check=='2') ? 'btn-primary':'btn-outline-primary'}}'
                                                            style="font-size:0.5rem" name="individual_text"
                                                            value="{{$val->id}}">있음
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row w-100 ml-0">
                                            <div class="col-2 p-0">개시</div>
                                            <div class="col-10">
                                                <div class="row" id="{{$val->id}}_open">
                                                    <button type="button"
                                                            class='col-3 p-1 m-1 {{($val->state=='2') ? 'btn-primary':'btn-outline-primary'}}'
                                                            style="font-size:0.5rem" name="individual_close"
                                                            value="{{$val->id}}">비게시
                                                    </button>
                                                    <button type="button"
                                                            class='col-3 p-1 m-1 {{($val->state=='1') ? 'btn-primary':'btn-outline-primary'}}'
                                                            style="font-size:0.5rem" name="individual_open"
                                                            value="{{$val->id}}">게시
                                                    </button>
                                                    <button type="button"
                                                            class='col-3 p-1 m-1 {{($val->state=='0') ? 'btn-primary':'btn-outline-primary'}}'
                                                            style="font-size:0.5rem" name="not_checked_to_open"
                                                            value="{{$val->id}}" disabled>미검수
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="checkbox" name="check_item[]" id="{{$val->id}}" value="{{$val->id}}"
                                   class="custom-control-input">
                            <label class="custom-control-label " for="{{$val->id}}">
                                <div id="created"
                                     class="font-weight-bold  {{($val->text_check==0)? 'not-text-checked' : '' }}"
                                     style="{{($val->text_check==2) ? 'background:red':''}}">
                                    @if($params['board_id'] != null)
                                        <a href="/admin/boards/{{$val->id}}/edit?type={{$val->type}}">
                                            @elseif($val->type == 'instagram')
                                                <a href="https://www.{{$val->type}}.com{{$val->post}}" target="_blank">
                                                    {{--@elseif($val->type=='youtube')--}}
                                                    {{--<a href = 'https://www.youtube.com/watch?v={{$val->post}}' target='_blank'>--}}
                                                    @else
                                                        <a href="/admin/boards/{{$val->id}}/edit?type={{$params['type']}}">
                                                            @endif
                                                            {{$val->created_at}}
                                                            {{--{{$val->id}}--}}
                                                        </a>
                                        {{--<button type='button' name="modify_tag" value="{{$val->id}}">태그</button>--}}
                                </div>
                                <img src="{{env('CDN_URL').$val->thumbnail_url}}" value="{{$val->id}}">
                                <div class="face-number"><h4 style="color: yellow;">{{$val->face_check}}</h4></div>
                                @if(isset($val->new))
                                    <div style="position: absolute; background: black;top:30px;right:0%;"><h4
                                                style="color: yellow;">new</h4></div>
                                @endif
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
                <div class="row justify-content-md-center invisible text-checking" id="text_check_loading"><img
                            src="{{env('PUBLIC_PATH')}}/images/loading_spinner.gif" height="500" width="500"></div>
            </form>
        </div>

        <div class="row justify-content-md-center invisible" id="ajax_loading"><img
                    src="{{env('PUBLIC_PATH')}}/images/loading_spinner.gif" height="100" width="100"></div>

        {{--2019.01.23 cch alert 추가        --}}
        <div id="dialog-form">
            <h4 class="font-weight-bold mt-5" style="text-align: center">"게시 전 한번 더 확인해주세요!"</h4>
            <hr>
            <div id="open">
                <div>
                    <ul>
                        <button v-for="tag in tags" v-text="tag" class="tag" v-on:click="removeTag"></button>
                    </ul>
                </div>
                <hr>
                {{--            <open_autocomplete :items= {!! json_encode($tag_list) !!} />--}}
                <open_autocomplete :items='{{ $tag_list }}'/>
            </div>
            {{--<input type="checkbox" class="custom-control-input" id="app_review" value=true>--}}
            {{--<label class="custom-control-label" for="app_review">--}}
            {{--<h6 class="text-danger text-center ">선택한 사진(들) 및 등록한 # (Tag)를 검수용으로 등록하시겠습니까?</h6>--}}
            {{--</label>--}}
            <h4 class="mt-4 text-center text-danger">선택한 사진(들) 및 등록한 # (Tag)를 게시하시겠습니까?</h4>
            <button type="submit" id="btn_real_open" name="btn_real_open" class="btn mt-4 font-weight-bold"
                    style="width: 50%;margin-left: 175px;">게시하기
            </button>
        </div>
        {{--@end 2019.01.23 cch alert 추가        --}}

        {{--2019.01.28 cch 공통태그 alert 추가--}}
        <div id="modify_tag_form">
            <h4 class="font-weight-bold mt-5" style="text-align: center">"개별 태그 수정"</h4>
            <hr>
            {{--2019.01.31 cch 태그 버튼구현 테스트--}}
            <div id="modify">
                <div>
                    <h4 class="font-weight-bold">A 태그</h4>
                    <ul>
                        <button v-for="ori in ori_tags" v-text="ori" class="tag"></button>
                    </ul>
                </div>
                <hr>
                <div>
                    <h4 class="font-weight-bold">B 태그</h4>
                    <ul>
                        <button v-for="tag in tags" v-text="tag" class="tag" v-on:click="removeTag"></button>
                    </ul>
                </div>
                {{--<modify_autocomplete :items= {!! json_encode($tag_list) !!} />--}}
                <modify_autocomplete :items='{{ $tag_list }}'/>
            </div>
            <button type="submit" id="btn_modify_tags" name="btn_modify_tags" class="btn mt-4 font-weight-bold"
                    style="width: 50%;margin-left: 175px;">수정하기
            </button>
            {{--2019.01.31 cch 태그 버튼구현 테스트--}}
        </div>
        {{--2019.01.28 cch 공통태그 alert 추가--}}

        {{--2019.03.11 cch 개별 수정 폼 추가--}}
        <div id="individual_modify_tag_form">
            <h4 class="font-weight-bold mt-5" style="text-align: center">"태그 수정"</h4>
            <hr>
            {{--2019.01.31 cch 태그 버튼구현 테스트--}}
            <div id="individual_modify">
                <div>
                    <h4 class="font-weight-bold">A 태그</h4>
                    <ul>
                        <button v-for="ori in ori_tags" v-text="ori" class="tag"></button>
                    </ul>
                </div>
                <hr>
                <div>
                    <h4 class="font-weight-bold">B 태그</h4>
                    <ul>
                        <button v-for="tag in tags" v-text="tag" class="tag" v-on:click="removeTag"></button>
                    </ul>
                </div>
                {{--                <modify_autocomplete :items= {!! json_encode($tag_list) !!} />--}}
                <modify_autocomplete :items='{{ $tag_list }}'/>
            </div>
            <button type="submit" id="btn_individual_modify_tags" name="btn_individual_modify_tags"
                    class="btn mt-4 font-weight-bold" style="width: 50%;margin-left: 175px;">수정하기
            </button>
            {{--2019.01.31 cch 태그 버튼구현 테스트--}}
        </div>
        {{--개별 수정 폼 추가--}}


        {{--2019.2.12 cch InputTool modal로 변경--}}
        <div id="create_form" style="overflow-x:hidden">
            {{--2019.01.18 cch 게시물작성폼--}}
            <h4>Input Tool</h4>
            <form enctype="multipart/form-data" id="create_{{$params['type']}}" name="create_{{$params['type']}}"
                  method="POST" action="{{url('/admin/boards?type='.$params['type'])}}">
                <input type="hidden" id="create_tag" name="create_tag">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label for="tags" class="font-weight-bold col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="font-weight-bold col-sm-2 col-form-label">url</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="url" name="url">
                    </div>
                </div>
                <div class="form-group row" id="input">
                    <label for="tags" class="font-weight-bold col-sm-2">#(Tag)</label>
                    <div class="col-sm-10">
                        <ul>
                            <button type="button" v-for="tag in tags" v-text="tag" class="tag"
                                    v-on:click="removeTag"></button>
                        </ul>
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        {{--<input_autocomplete :items= {!! json_encode($tag_list) !!} />--}}
                        <input_autocomplete :items='{{ $tag_list }}'/>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="thumbnails" class="font-weight-bold col-sm-2">이미지 업로드</label>
                    <div class="col-sm-10">
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control-file"
                               style="background: white; width: 100%;">
                        <img id="blah" src="#" alt="need image"/>
                    </div>
                </div>
            </form>
            <button type="submit" id="create_article" name="create_article" class="btn mt-4 font-weight-bold"
                    style="width: 50%;margin-left: 175px;color:black;">등록
            </button>
            {{--2019.01.18 cch 게시물작성폼  index로 포함--}}
        </div>
    </main>
@endsection

@push('script')
    {{--<script src="/js/app.js"></script>--}}
    <script src="/js/board_control.js"></script>

    {{--<script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/boardcontrol.js?version=0.5.1"></script>--}}
    {{--<script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/tag_list_search.js?version=0.5.1"></script>--}}
    <script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/search_list_search.js?version=0.5.1"></script>
    {{--<script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/menu/controlmenu.js?version=0.5.1"></script>--}}
    {{--<script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/menu/sidemenu.js?version=0.5.1"></script>--}}
    {{--<script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/menu/dropmenu.js?version=0.5.1"></script>--}}
    {{--<script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/modal/open.js?version=0.5.1"></script>--}}
    {{--<script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/modal/modify.js?version=0.5.1"></script>--}}
    {{--<script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/modal/individual_modify.js?version=0.5.1"></script>--}}
    {{--<script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/modal/create.js?version=0.5.1"></script>--}}
    <script>
        //php 변수 자바스크립트로 넘기는 코드 js파일로 뜯어노면 오류나서 남겨둠
        var title = "<?= $params['type']?>";
        var token = "{{csrf_token()}}";
    </script>
@endpush
