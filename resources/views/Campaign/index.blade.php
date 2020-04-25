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
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin">홈</a>
            </li>
            <li class="breadcrumb-item">마케팅 관리</li>
            <li class="breadcrumb-item active"><strong>광고 관리</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="icon-screen-smartphone"></i>광고 관리</div>
                                <div class="float-right">
                                    {{--<a href="/admin/campaigns/create" class="btn btn-success mb-2" role="button">새 게시물</a>--}}
                                    <button type="button" id="create_campaign_button" class="btn btn-success mb-2">new</button>
                                    <button id="delete_button" class="btn btn-danger mb-2">삭제</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="callout callout-warning">
                                                    <small class="text-muted">검색결과</small>
                                                    <br>
                                                    <strong class="h4">{{ isset($search_count)? $search_count: 0 }}</strong>
                                                    <div class="chart-wrapper">
                                                        <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="callout callout-info">
                                                    <small class="text-muted">총 개수</small>
                                                    <br>
                                                    <strong class="h4">{{ isset($total)? $total: 0 }}</strong>
                                                    <div class="chart-wrapper">
                                                        <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt-0 mb-4">
                                <form class="form-inline" method="GET" action="{{url('/admin/campaigns')}}">
                                    {{--<input type="hidden" id="last" name="last" value="{{$params['last']}}">--}}
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row pl-3">
                                                <div class="form-inline form-group">
                                                    <div class="col"><label for="page_cnt">게시물 수: </label></div>
                                                    <div class="input-group">
                                                        <select class="form-control" name="page_cnt" id="page_cnt">
                                                            <option value="15" {{ ($params['page_cnt']=='15') ? 'selected' : '' }}>15</option>
                                                            <option value="30" {{ ($params['page_cnt']=='30') ? 'selected' : '' }}>30</option>
                                                            <option value="50" {{ ($params['page_cnt']=='50') ? 'selected' : '' }}>50</option>
                                                            <option value="100" {{ ($params['page_cnt']=='100') ? 'selected' : '' }}>100</option>
                                                            <option value="1000" {{ ($params['page_cnt']=='1000') ? 'selected' : '' }}>1000</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-inline form-group">
                                                    <div class="col"><label for="start_date">검색 기간: </label></div>
                                                    <div class="input-group mr-1">
                                                        <input type="text" class="form-control datetimepicker" id="start_date" name="start_date" value="{{$params['start_date']}}" placeholder="" autocomplete="off">
                                                    </div>
                                                    ~
                                                    <div class="input-group">
                                                        <input type="text" class="form-control datetimepicker" id="end_date" name="end_date" value="{{$params['end_date']}}" placeholder="" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-inline form-group">
                                                    <div class="col"><label for="state">상태: </label></div>
                                                    <div class="input-group">
                                                        <select class="form-control" name="state" id="state">
                                                            <option value="" {{ ($params['state']=='') ? 'selected' : '' }}>전체</option>
                                                            <option value="0" {{ ($params['state']=='0') ? 'selected' : '' }}>대기</option>
                                                            <option value="1" {{ ($params['state']=='1') ? 'selected' : '' }}>게시</option>
                                                        </select>
                                                    </div>
                                                    <button class="btn btn-primary ml-2" type="submit"><i class="fa fa-search"></i> 검색</button>
                                                    {{--<button type="submit" class="btn btn-primary mb-2">검색</button>--}}
                                                    <button class="btn btn-secondary ml-2 btn-refresh" type="button"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--<div class="form-group mb-2">--}}
                                        {{--<h6 class="font-weight-bold">등록 날짜</h6>--}}
                                        {{--<input type="text" class="form-control datetimepicker" id="start_date" name="start_date" value="{{$params['start_date']}}">--}}
                                        {{--~--}}
                                        {{--<input type="text" class="form-control datetimepicker" id="end_date" name="end_date" value="{{$params['end_date']}}">--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group mx-sm-2 mb-2">--}}
                                        {{--<h6 class="font-weight-bold">상태</h6>--}}
                                        {{--<select class="form-control" name="state" id="state">--}}
                                            {{--<option value="" {{ ($params['state']=='') ? 'selected' : '' }}>전체</option>--}}
                                            {{--<option value="0" {{ ($params['state']=='0') ? 'selected' : '' }}>대기</option>--}}
                                            {{--<option value="1" {{ ($params['state']=='1') ? 'selected' : '' }}>게시</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group mx-sm-2 mb-2">--}}
                                        {{--<h6 class="font-weight-bold">게시물 수</h6>--}}
                                        {{--<select class="form-control" name="page_cnt" id="page_cnt">--}}
                                            {{--<option value="15" {{ ($params['page_cnt']=='15') ? 'selected' : '' }}>15</option>--}}
                                            {{--<option value="30" {{ ($params['page_cnt']=='30') ? 'selected' : '' }}>30</option>--}}
                                            {{--<option value="50" {{ ($params['page_cnt']=='50') ? 'selected' : '' }}>50</option>--}}
                                            {{--<option value="100" {{ ($params['page_cnt']=='100') ? 'selected' : '' }}>100</option>--}}
                                            {{--<option value="1000" {{ ($params['page_cnt']=='1000') ? 'selected' : '' }}>1000</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                    {{--<button type="submit" class="btn btn-primary mb-2">검색</button>--}}
                                </form>
                                <hr class="mt-2 mb-4">


                                <form id='delete_post' method="POST" action="{{url('/board/ads/delete')}}">
                                    {{ csrf_field() }}

                                    {{--<div class="container-fluid">--}}
                                        {{--<div class="row py-1">--}}
                                            {{--<div class="col">--}}
                                                {{--<button type="submit" class="btn btn-danger mb-2">삭제</button>--}}
                                                {{--<a href="/board/ads/form/0" class="btn btn-success mb-2" role="button">새 게시물</a>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-center"><input type="checkbox" name="check_all" id="check_all"></th>
                                                {{--<th class="text-center">광고주</th>--}}
                                                <th class="text-center">미리보기</th>
                                                <th class="text-center"></th>
                                                <th class="text-center">설명</th>
                                                <th class="text-center">상태</th>
                                                <th class="text-center">광고 시작일</th>
                                                <th class="text-center">광고 종료일</th>
                                                <th class="text-center">등록 날짜</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($rows as $val)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="check_item[]" id="check_item" value="{{$val->id}}">
                                                </td>
                                                {{--<td class="text-center">--}}
                                                    {{--{{$val->ads_name}}--}}
                                                {{--</td>--}}
                                                <td class="text-center">
                                                    @if (isset($val->img_url))
                                                        <img src="{{env('CDN_URL').$val->img_url}}" height="100" width="100">
                                                    @else
                                                        미리보기 없음
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{$val->title}}
                                                </td>
                                                <td class="text-center">
                                                    <a href="/admin/campaigns/{{$val->id}}/edit">{{$val->description}}</a>
                                                </td>
                                                <td class="text-center">
                                                    <div class="mt-2">
                                                        <label class="switch switch-3d switch-success">
                                                            <input class="switch-input campaign-activated" id="{{ $val->id }}" type="checkbox" {{ ($val->state == 1 ? 'checked' : '') }}>
                                                            <span class="switch-slider"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                {{--<td class="text-center">--}}
                                                    {{--@if($val->state==0)--}}
                                                        {{--<span class="badge badge-danger">대기</span>--}}
                                                    {{--@else--}}
                                                        {{--<span class="badge badge-success">게시</span>--}}
                                                    {{--@endif--}}
                                                {{--</td>--}}
                                                <td class="text-center">
                                                    {{$val->start_date}}
                                                </td>
                                                <td class="text-center">
                                                    {{$val->end_date}}
                                                </td>
                                                <td class="text-center">
                                                    {{$val->created_at}}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    {{--<div class="container-fluid">--}}
                                        {{--<div id="contents">--}}
                                        {{--@foreach ($rows->Documents as $val)--}}
                                            {{--<div class="row py-2">--}}
                                                {{--<div class="col-sm-1">--}}
                                                    {{--<input type="checkbox" name="check_item[]" id="check_item" value="{{$val->id}}">--}}
                                                {{--</div>--}}
                                                {{--<div class="col-sm-1">--}}
                                                    {{--{{$val->ads_name}}--}}
                                                {{--</div>--}}
                                                {{--<div class="col-sm-1">--}}
                                                    {{--@if (isset($val->thumbnail_1_1))--}}
                                                        {{--<img src="{{env('CDN_URL').$val->thumbnail_1_1}}" height="100" width="100">--}}
                                                    {{--@else--}}
                                                        {{--미리보기 없음--}}
                                                    {{--@endif--}}
                                                {{--</div>--}}
                                                {{--<div class="col-sm">--}}
                                                    {{--<a href="/board/ads/form/{{$val->id}}">{{$val->contents}}</a>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-sm-1">--}}
                                                    {{--@if($val->state==0)--}}
                                                        {{--<span class="badge badge-danger">대기</span>--}}
                                                    {{--@else--}}
                                                        {{--<span class="badge badge-success">게시</span>--}}
                                                    {{--@endif--}}
                                                {{--</div>--}}
                                                {{--<div class="col-sm-1">--}}
                                                    {{--{{$val->start_date}}--}}
                                                {{--</div>--}}
                                                {{--<div class="col-sm-1">--}}
                                                    {{--{{$val->end_date}}--}}
                                                {{--</div>--}}
                                                {{--<div class="col-sm-1">--}}
                                                    {{--{{$val->created_date}}--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<hr/>--}}
                                        {{--@endforeach--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addcampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" method="POST" action="{{url('/admin/campaigns')}}" onsubmit="return value_check()">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"><strong>캠페인 추가</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="event_type" class="col-sm-2 col-form-label">타입</label>
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
                                <label for="order_num" class="col-sm-2 col-form-label">노출 순서</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="order_num" id="order_num"  min="0" step="1">
                                </div>
                            </div>
                            <div class="form-group row select_type M I F">
                                <label for="img_url" class="col-sm-2 col-form-label">로고</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="img_url"  id="img_url">
                                </div>
                            </div>
                            <div class="form-group row select_type M I F">
                                <label for="repeat" class="col-sm-2 col-form-label">반복 시간(분)/일회성 = 0</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="repeat" id="repeat" min="0" step="1">
                                </div>
                            </div>
                            <div class="form-group row select_type M I F">
                                <label for="app_package" class="col-sm-2 col-form-label">앱 페키지명</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="app_package" id="app_package">
                                </div>
                            </div>
                            <div class="form-group row select_type M">
                                <label for="push_title" class="col-sm-2 col-form-label">push_title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="push_title" id="push_title">
                                </div>
                            </div>
                            <div class="form-group row select_type M">
                                <label for="push_message" class="col-sm-2 col-form-label">psuh_message</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="push_message" id="push_message">
                                </div>
                            </div>
                            <div class="form-group row select_type M">
                                <label for="push_tick" class="col-sm-2 col-form-label">push_tick</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="push_tick" id="push_tick">
                                </div>
                            </div>
                            <div class="form-group row select_type C">
                                <label for="thumbnail_1_1" class="col-sm-2 col-form-label">이미지_1_1</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="thumbnail_1_1" id="thumbnail_1_1">
                                </div>
                            </div>
                            <div class="form-group row select_type C">
                                <label for="thumbnail_2_1" class="col-sm-2 col-form-label">이미지_2_1</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="thumbnail_2_1" id="thumbnail_2_1">
                                </div>
                            </div>
                            <div class="form-group row select_type C">
                                <label for="thumbnail_3_1" class="col-sm-2 col-form-label">이미지_3_1</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="thumbnail_3_1" id="thumbnail_3_1">
                                </div>
                            </div>
                            <div class="form-group row select_type C">
                                <label for="thumbnail_1_2" class="col-sm-2 col-form-label">이미지_1_2</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="thumbnail_1_2" id="thumbnail_1_2">
                                </div>
                            </div>
                            <div class="form-group row select_type C">
                                <label for="thumbnail_2_2" class="col-sm-2 col-form-label">이미지_2_2</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="thumbnail_2_2" id="thumbnail_2_2">
                                </div>
                            </div>
                            <div class="form-group row select_type C">
                                <label for="thumbnail_3_3" class="col-sm-2 col-form-label">이미지_3_3</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="thumbnail_3_3" id="thumbnail_3_3">
                                </div>
                            </div>
                            <hr>
                            <hr>
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label">캠페인 이름</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">캠페인 설명</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="description" id="description">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="url" class="col-sm-2 col-form-label">url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="url" id="url">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="item_count" class="col-sm-3 col-form-label">보상 개수</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="item_count" id="item_count"  min="0" step="5">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="push_type" class="col-sm-3 col-form-label">광고기간</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control datetimepicker" name="start_date" id="start_date" autocomplete="off">
                                        </div>
                                        ~
                                        <div class="col">
                                            <input type="text" class="form-control datetimepicker"  name="end_date" id="end_date" autocomplete=s"off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-dot-circle-o"></i> 등록</button>
                            <button class="btn btn-sm btn-danger" type="reset"><i class="fa fa-refresh"></i> 다시</button>
                            <button class="btn btn-sm btn-secondary" type="button" data-dismiss="modal"><i class="fa fa-ban"></i> 닫기</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@stop

@push('script')
    <script>
        var accessToken = '{{ Session::get('access_token')  }}';
        var tokenType = '{{ Session::get('token_type') }}';

        var update_lock = false;
        {{--var sort_key = '{{ $params['sort_key'] }}';--}}
        {{--var sort_value = '{{ $params['sort_value'] }}';--}}

        $('#create_campaign_button').on('click',function(e){
            e.preventDefault();

            $('#addcampaignModal').modal('show');
            $('#package').focus();
        });

        // 초기화 클릭
        $('.btn-refresh').on('click', function (e) {

            e.preventDefault();

            location.href = location.pathname;
        });
        $(document).ready(function(){
            $('#delete_button').click(function(){
                $('#delete_post').submit();
            });
            // checkbox all check
            $("#check_all").click(function(){
                if($("#check_all").prop("checked")){
                    $("input[id=check_item]").prop("checked",true);
                }else{
                    $("input[id=check_item]").prop("checked",false);
                }
            });

            $('#event_type').change(function(){
                $('.select_type').css('display','none');
                selected_type = $("#event_type option:selected").val();
                $('.'+selected_type).css('display','flex');
            });

            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });
        });

        // 활성 변경
        $('.campaign-activated').on('click', function (e) {
            updatecampaign(this.id, ($(this).is(':checked') ? '1' : '0'));

        });

        // 앱 권한 \수정
        function updatecampaign(id, state)
        {
            if (update_lock == true) {
                swal.fire('수정에 실패하였습니다!');
                return false;
            } else {
                update_lock = true;
            }

            var params = {
                _method : 'PUT',
                state : state
            };

            $.ajax({
                url: '/api/campaigns/'+id+'/state',
                headers: {
                    'Accept' : 'application/json',
                    'Authorization' : tokenType + ' ' + accessToken,
                },
                data: params,
                type: 'put',
                beforeSend: function () {
                }
            }).
            done(function (response) {
                var result = response.result;

                if (result == 'success') {
                    swal.fire('수정하였습니다')
                } else {
                    swal.fire('수정에 실패하였습니다!');
                }
            }).
            fail(function (response) {
                console.log(response); console.log(response)
                swal.fire('수정에 실패하였습니다!');
            }).
            always(function (response) {
                console.log(response);
                update_lock = false;
            });
        }
    </script>
@endpush
