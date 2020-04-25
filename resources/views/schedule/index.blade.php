@extends('layouts.master')

@push('style')
    <style>
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
            <li class="breadcrumb-item active"><strong>스케줄 관리</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="icon-screen-smartphone"></i>스케줄 관리</div>
                                <div class="float-right">
                                    {{--<a href="/admin/campaigns/create" class="btn btn-success mb-2" role="button">새 게시물</a>--}}
                                    <button type="button" id="create_schedule_button" class="btn btn-success mb-2">new</button>
                                    <button id="delete_button" class="btn btn-danger mb-2" disabled>삭제(미완성)</button>
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
                                <form class="form-inline" method="GET" action="{{url('/admin/schedules')}}">
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
                                                    <button class="btn btn-primary ml-2" type="submit"><i class="fa fa-search"></i> 검색</button>
                                                    {{--<button type="submit" class="btn btn-primary mb-2">검색</button>--}}
                                                    <button class="btn btn-secondary ml-2 btn-refresh" type="button"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr class="mt-2 mb-4">
                                <form id='delete_post' method="POST" action="{{url('/admin/schedules/bulk')}}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="delete">
                                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th class="text-center"><input type="checkbox" name="check_all" id="check_all"></th>
                                            <th class="text-center">일정</th>
                                            <th class="text-center">제목</th>
                                            <th class="text-center">내용</th>
                                            <th class="text-center">등록일</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($rows as $val)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="check_item[]" id="check_item" value="{{$val->id}}">
                                                </td>
                                                <td class="text-center">
                                                    {{$val->scheduled_at}}
                                                </td>
                                                <td class="text-center">
                                                    {{$val->title}}
                                                </td>
                                                <td class="text-center">
                                                    {{$val->contents}}
                                                </td>
                                                <td class="text-center">
                                                    {{$val->created_at}}
                                                </td>
                                                <td>
                                                    <button type='button' class="btn btn-outline-primary">수정(미완성)</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addscheduleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" method="POST" action="{{url('/admin/schedules')}}" onsubmit="return value_check()">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"><strong>스케줄 등록</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="create_scheduled_at" class="col-sm-2 col-form-label">날짜</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control datetimepicker"
                                           id="create_scheduled_at" name="create_scheduled_at"
                                           placeholder="" autocomplete="off"
                                    value="{{\Carbon\Carbon::now()->startOfHour()->toDateTimeString()}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="create_title" class="col-sm-2 col-form-label">제목</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="create_title" id="create_title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="create_contents" class="col-sm-2 col-form-label">내용</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="create_contents"  id="create_contents">
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

        $('#create_schedule_button').on('click',function(e){
            e.preventDefault();
            $('#addscheduleModal').modal('show');
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

            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d H:i',
                timepicker: true,
                step:30,
            });
        });
    </script>
@endpush
