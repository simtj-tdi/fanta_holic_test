@extends('layouts.master')

@push('style')
    <style>
        .push_type_I {
            display: none;
        }
        .action_M {
            display: none;
        }
        .action_B {
            display: none;
        }
        .action_S {
            display: none;
        }
    </style>
@endpush

@section('content')
    <main class="main">

        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">홈</a>
            </li>
            <li class="breadcrumb-item">Push</li>
            <li class="breadcrumb-item active"><strong>등록</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="icon-screen-smartphone"></i>Push 등록</div>
                                {{--<div class="float-right">--}}
                                    {{--/app_trends/excel--}}
                                    {{--<a href="{{ url('/admin/app_trends/excel?period='.$params['period'].'&gender='.$params['gender'].'&age='.$params['age']) }}"--}}
                                       {{--class="btn btn-success"><i class="fa fa-download"></i> 엑셀 다운로드</a>--}}
                                {{--</div>--}}
                            </div>
                            <div class="card-body">
                                @if ($id == '0')
                                    <form enctype="multipart/form-data" method="POST" action="{{
                                    url('/admin/pushes')
                                    }}">
                                @else
                                   <form enctype="multipart/form-data" method="PUT" action="{{
                                    url("/admin/pushes/{$id}")
                                    }}">
                                @endif
                                <form enctype="multipart/form-data" method="POST" action="{{
                                    ($id == '0') ? url('/pushes') : url('/push/update')
                                    }}">
                                {{--@if ($id == '0')--}}
                                {{--<form enctype="multipart/form-data" method="POST" action="{{url('/push/store')}}">--}}
                                {{--@else--}}
                                {{--<form enctype="multipart/form-data" method="POST" action="{{url('/push/update')}}">--}}
                                {{--@endif--}}
                                    {{ csrf_field() }}
                                    <input type="hidden" id="id" name="id" value="{{$id}}">
{{--                                    <input type="hidden" id="batch_type" name="batch_type" value="{{$batch_type}}">--}}
                                    {{--<div class="form-group row">--}}
                                        {{--<label for="push_type" class="col-sm-2 col-form-label">개발test용 </label>--}}
                                        {{--<div class="col-sm-10">--}}
                                            {{--<input type="number" class="form-control" name="many" id="many">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
{{--                                    @if ($batch_type == 'P')--}}
{{--                                    <div class="form-group row">--}}
{{--                                        <label for="title" class="col-sm-2 col-form-label">유저 아이디</label>--}}
{{--                                        <div class="col-sm-10">--}}
{{--                                            <input type="text" class="form-control" id="user_id" name="user_id" value="{{isset($rows->user_id)? $rows->user_id : $user_id}}" readonly>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @endif--}}
                                    <div class="form-group row">
                                        <label for="title" class="col-sm-2 col-form-label">Title <font color="red">*</font></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="title" name="title" value="{{isset($rows->title)? $rows->title : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="contents" class="col-sm-2 col-form-label">Contents <font color="red">*</font></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="contents" name="contents" value="{{isset($rows->contents)? $rows->contents : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tick" class="col-sm-2 col-form-label">Tick <font color="red">*</font></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="tick" name="tick" value="{{isset($rows->tick)? $rows->tick : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">Push
                                            범위</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="batch_type"
                                                    id="batch_type">
                                                <option value="">선택해주세요</option>
                                                <option value="P" @if (isset($rows->batch_type)) {{($rows->batch_type=='P')? 'selected' : ''}} @endif>
                                                    개별
                                                </option>
                                                <option value="A" @if (isset($rows->batch_type)) {{($rows->batch_type=='A')? 'selected' : ''}} @endif>
                                                    전체
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row personForm d-none">
                                        <label for="title" class="col-sm-2 col-form-label">유저
                                            아이디</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                                   id="user_id"
                                                   name="user_id"
                                                   placeholder="유저 아이디를 입력해주세요(여러명일경우 `,`를 입력해주세요)"
                                                   value="{{ $rows->user_id ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">Push Type</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="push_type" id="push_type">
                                                <option value="T" @if (isset($rows->push_type)) {{($rows->push_type=='T')? 'selected' : ''}} @endif>Text</option>
                                                <option value="I" @if (isset($rows->push_type)) {{($rows->push_type=='I')? 'selected' : ''}} @endif>Image</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="push_type_I">
                                    <div class="form-group row">
                                        <label for="img_url" class="col-sm-2 col-form-label">Push Image</label>
                                        <div class="col-sm-10">
                                            @if (isset($rows->img_url))
                                                <img src="{{$rows->img_url}}">
                                            @endif
                                            <input type="file" name="img_url" id="img_url">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="action" class="col-sm-2 col-form-label">터치 시 행동</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="action" id="action">
                                                <option value="" selected disabled hidden>터치 시 행동 선택</option>
{{--                                                @if((Session::get('app')=='leeseol'))--}}
                                                    <option value="A" @if (isset($rows->action)) {{($rows->action=='A')? 'selected' : ''}} @endif>앱 실행</option>
                                                    <option value="M" @if (isset($rows->action)) {{($rows->action=='M')? 'selected' : ''}} @endif>지정한 URL로 이동</option>
                                                {{--@endif--}}
                                                <option value="B" @if (isset($rows->action)) {{($rows->action=='B')? 'selected' : ''}} @endif>특정 게시물로 이동</option>
                                                <option value="S" @if (isset($rows->action)) {{($rows->action=='S')? 'selected' : ''}} @endif>멜론 스트리밍</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="action_S">
                                        <div class="form-group row">
                                            <label for="campaign_id" class="col-sm-2 col-form-label">streaming</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="campaign_id" id="campaign_id">
                                                    <option value=""  @if($rows != null && isset(json_decode($rows->streaming_url)->id))@endif disabled selected> 선택 </option>
                                                    @foreach($melon_streaming_campaigns as $melon_streaming)
                                                        <option value = "{{$melon_streaming->id}}" @if($rows != null && isset(json_decode($rows->streaming_url)->id)) {{(json_decode($rows->streaming_url)->id == $melon_streaming->id) ? 'selected':''}} @endif>{{$melon_streaming->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action_M">
                                        <div class="form-group row">
                                            <label for="url" class="col-sm-2 col-form-label">URL</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="url" name="url" value="{{isset($rows->url)? $rows->url : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action_B">
                                        <div class="form-group row">
                                            <label for="board_type" class="col-sm-2 col-form-label">게시물 타입</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="board_type" id="board_type">
                                                    @if((Session::get('app')=='leeseol'))
                                                        <option value="ads" @if (isset($rows->board_type)) {{($rows->board_type=='ads')? 'selected' : ''}} @endif>광고</option>
                                                        <option value="notice" @if (isset($rows->board_type)) {{($rows->board_type=='notice')? 'selected' : ''}} @endif>공지</option>
                                                        <option value="news" @if (isset($rows->board_type)) {{($rows->board_type=='news')? 'selected' : ''}} @endif>뉴스</option>
                                                    @else
                                                        <option value="vlive" @if (isset($rows->board_type)) {{($rows->board_type=='vlive')? 'selected' : ''}} @endif>V-live</option>
                                                        <option value="twitter" @if (isset($rows->board_type)) {{($rows->board_type=='twitter')? 'selected' : ''}} @endif>트위터</option>
                                                    @endif
                                                    <option value="instagram" @if (isset($rows->board_type)) {{($rows->board_type=='instagram')? 'selected' : ''}} @endif>인스타그램</option>
                                                    <option value="youtube" @if (isset($rows->board_type)) {{($rows->board_type=='youtube')? 'selected' : ''}} @endif>유튜브</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action_B">
                                        <div class="form-group row">
                                            <label for="board_id" class="col-sm-2 col-form-label">게시물 아이디</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="board_id" name="board_id" value="{{isset($rows->board_id)? $rows->board_id : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="start_date" class="col-sm-2 col-form-label">발송 예정 시간</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control datetimepicker" id="start_date" name="start_date"
                                                   value="{{isset($rows->start_date)? $rows->start_date : \Carbon\Carbon::now('Asia/Seoul')->toDateString()." 00:00:00"}}">
                                        </div>
                                    </div>

                                    @if ($id != '0')
                                    <div class="form-group row">
                                        <label for="state" class="col-sm-2 col-form-label">상태</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="state" name="state" value="{{isset($rows->state)? $config['state'][$rows->state] : ''}}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="success" class="col-sm-2 col-form-label">성공</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="success" name="success" value="{{isset($rows->success)? $rows->success : ''}}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="fail" class="col-sm-2 col-form-label">실패</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="fail" name="fail" value="{{isset($rows->fail)? $rows->fail : ''}}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="updated_date" class="col-sm-2 col-form-label">발송 완료 시간</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="updated_date" name="updated_date" value="{{isset($rows->updated_date)? $rows->updated_date : ''}}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="created_date" class="col-sm-2 col-form-label">등록 시간</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="created_date" name="created_date" value="{{isset($rows->created_date)? $rows->created_date : ''}}" readonly>
                                        </div>
                                    </div>
                                    @endif

                                    @if (($id != '0' && $rows->state == 'R') || ($id == '0'))
                                        <button type="submit" class="btn btn-primary">등록</button>
                                    @endif

                                    <a href="/admin/pushes"><button type="button" class="btn btn-light">목록</button></a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop

@push('script')
    <script>
        $(document).ready(function(){
            $('#batch_type').change(function () {
               let batch_type = $(this).val();
               if(batch_type == 'P') {
                   $('.personForm').removeClass('d-none');
               } else {
                   $('.personForm').addClass('d-none');
               }
            });

            if ($('#id').val() != '0') {
                ($("#push_type option:selected").val() == 'I') ?
                    $('.push_type_I').css('display', 'block') : $('.push_type_I').css('display', 'none');

                switch($("#action option:selected").val()) {
                    case 'S':
                        $('.action_S').css('display', 'block');
                        $('.action_M').css('display', 'none');
                        $('.action_B').css('display', 'none');
                        break;
                    case 'M':
                        $('.action_S').css('display', 'none');
                        $('.action_M').css('display', 'block');
                        $('.action_B').css('display', 'none');
                        break;
                    case 'B':
                        $('.action_S').css('display', 'none');
                        $('.action_M').css('display', 'none');
                        $('.action_B').css('display', 'block');
                        break;
                    default:
                        $('.action_S').css('display', 'none');
                        $('.action_M').css('display', 'none');
                        $('.action_B').css('display', 'none');
                }
            }

            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d H:i:00',
                step: 5
            });

            // Push Type 에 따라 추가 설정
            $('#push_type').change(function() {
                ($("#push_type option:selected").val() == 'I') ?
                    $('.push_type_I').css('display', 'block') : $('.push_type_I').css('display', 'none');
            });

            // Action 에 따라 추가 설정
            $('#action').change(function() {
                switch($("#action option:selected").val()) {
                    case 'S':
                        $('.action_S').css('display', 'block');
                        $('.action_M').css('display', 'none');
                        $('.action_B').css('display', 'none');
                        break;
                    case 'M':
                        $('.action_S').css('display', 'none');
                        $('.action_M').css('display', 'block');
                        $('.action_B').css('display', 'none');
                        break;
                    case 'B':
                        $('.action_S').css('display', 'none');
                        $('.action_M').css('display', 'none');
                        $('.action_B').css('display', 'block');
                        break;
                    default:
                        $('.action_S').css('display', 'none');
                        $('.action_M').css('display', 'none');
                        $('.action_B').css('display', 'none');
                }
            });
        });
    </script>
@endpush
