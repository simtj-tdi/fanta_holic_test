@extends('layouts.master')

@push('style')
    <style>
        {{--C: 클릭형 F: 친구초대 I: 설치형 M: 멜론스트리밍--}}
        .select_type {
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
            <li class="breadcrumb-item">Campaign</li>
            <li class="breadcrumb-item active"><strong>등록</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="icon-screen-smartphone"></i>Campaign 등록</div>
                            </div>
                            <div class="card-body">
                                <form enctype="multipart/form-data" method="POST" action="{{url('/admin/campaigns')}}">
                                    {{--타입 선택하면 필요한 값들만 표시--}}
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">타입</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="event_type" id="event_type">
                                                <option selected disabled>선택</option>
                                                <option value = 'M'>멜론 스트리밍</option>
                                                <option value = 'F'>친구 초대</option>
                                                <option value = 'I'>설치형</option>
                                                <option value = 'C'>클릭형</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C I M">
                                        <label for="push_type" class="col-sm-2 col-form-label">순서</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="order_num" id="order_num">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C M I F">
                                        <label for="push_type" class="col-sm-2 col-form-label">img_url</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M I F">
                                        <label for="push_type" class="col-sm-2 col-form-label">repeat</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M I F">
                                        <label for="push_type" class="col-sm-2 col-form-label">app_package</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M">
                                        <label for="push_type" class="col-sm-2 col-form-label">push_title</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M">
                                        <label for="push_type" class="col-sm-2 col-form-label">psuh_message</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type M">
                                        <label for="push_type" class="col-sm-2 col-form-label">push_tick</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="push_type" class="col-sm-2 col-form-label">thumbnail_1_1</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="push_type" class="col-sm-2 col-form-label">thumbnail_2_1</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="push_type" class="col-sm-2 col-form-label">thumbnail_3_1</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="push_type" class="col-sm-2 col-form-label">thumbnail_1_2</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="push_type" class="col-sm-2 col-form-label">thumbnail_2_2</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row select_type C">
                                        <label for="push_type" class="col-sm-2 col-form-label">thumbnail_3_3</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <hr>
                                    <hr>
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">캠페인 이름</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="title" id="title">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">캠페인 설명</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="description" id="description">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">url</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="" id="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">캠페인 보상 개수</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="item_count" id="item_count">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">상태</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="state" id="state">
                                                <option selected disabled>선택</option>
                                                <option value="1">게시</option>
                                                <option value="0">비게시</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">광고시작일</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control datetimepicker" name="start_date" id="start_date" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="push_type" class="col-sm-2 col-form-label">광고종료일</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control datetimepicker"  name="end_date" id="end_date" autocomplete="off">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">수정</button>
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
            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });

            $('#event_type').change(function(){
                $('.select_type').css('display','none');
                selected_type = $("#event_type option:selected").val();
                $('.'+selected_type).css('display','flex');
            })
        });
    </script>
@endpush
