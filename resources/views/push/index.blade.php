@extends('layouts.master')

@section('content')
    <main class="main">

        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">홈</a>
            </li>
            {{--<li class="breadcrumb-item">콘텐츠</li>--}}
            <li class="breadcrumb-item">마케팅 관리</li>
            <li class="breadcrumb-item active"><strong>푸시 알림</strong></li>
        </ol>
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mt-2"><i class="icon-screen-smartphone"></i>Push</div>
                                <div class="float-right">
                                    <a href="/admin/pushes/create" class="btn btn-success mb-2" role="button">새 게시물</a>
                                    <button id="delete_button" class="btn btn-danger mb-2">삭제</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
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

                                <form class="form-inline" method="GET" action="{{url('/admin/pushes')}}">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row pl-3">
                                                <div class="form-inline form-group">
                                                        <div class="col"><label for="state">상태: </label></div>
                                                    <div class="input-group">
                                                        <select class="form-control" name="state" id="state">
                                                            <option value="R" @if (isset($params['state'])) {{($params['state']=='R')? 'selected' : ''}} @endif>대기</option>
                                                            <option value="S" @if (isset($params['state'])) {{($params['state']=='S')? 'selected' : ''}} @endif>발송중</option>
                                                            <option value="Y" @if (isset($params['state'])) {{($params['state']=='Y')? 'selected' : ''}} @endif>발송완료</option>
                                                            <option value="X" @if (isset($params['state'])) {{($params['state']=='X')? 'selected' : ''}} @endif>발송취소</option>
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
                                                {{--<button type="submit" class="btn btn-primary">검색</button>--}}
                                                <button class="btn btn-primary ml-2" type="submit"><i class="fa fa-search"></i> 검색</button>
                                                {{--<button type="submit" class="btn btn-primary mb-2">검색</button>--}}
                                                <button class="btn btn-secondary ml-2 btn-refresh" type="button"><i class="fa fa-refresh"></i></button>
                                            </div>
                                        </div>
                                    {{--</div>--}}
                                </div>


                                    {{--<div class="form-group mb-2">--}}
                                        {{--<h6 class="font-weight-bold">상태</h6>--}}
                                        {{--<select class="form-control" name="state" id="state">--}}
                                            {{--<option value="R" @if (isset($params['state'])) {{($params['state']=='R')? 'selected' : ''}} @endif>대기</option>--}}
                                            {{--<option value="S" @if (isset($params['state'])) {{($params['state']=='S')? 'selected' : ''}} @endif>발송중</option>--}}
                                            {{--<option value="Y" @if (isset($params['state'])) {{($params['state']=='Y')? 'selected' : ''}} @endif>발송완료</option>--}}
                                            {{--<option value="X" @if (isset($params['state'])) {{($params['state']=='X')? 'selected' : ''}} @endif>발송취소</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group mb-2">--}}
                                        {{--<h6 class="font-weight-bold">등록 날짜</h6>--}}
                                        {{--<input type="text" class="form-control datetimepicker" id="start_date" name="start_date" value="{{$params['start_date']}}">--}}
                                        {{--~--}}
                                        {{--<input type="text" class="form-control datetimepicker" id="end_date" name="end_date" value="{{$params['end_date']}}">--}}
                                    {{--</div>--}}
                                    {{--<button type="submit" class="btn btn-primary mb-2">검색</button>--}}
                                </form>
                                <hr class="mt-2 mb-4">
                                <form id='delete_post' method="POST" action="{{url('/admin/pushes/bulk')}}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    {{ csrf_field() }}

                                    {{--<div class="container-fluid">--}}
                                        {{--<div class="row py-1">--}}
                                            {{--<div class="col">--}}
                                                {{--<button type="submit" class="btn btn-danger mb-2">발송취소</button>--}}
                                                {{--<a href="/push/form/0" class="btn btn-success mb-2" role="button">새 게시물</a>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{ $rows->appends($params)->links() }}
                                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">  <input type="checkbox" name="check_all" id="check_all"></th>
                                            <th class="text-center">seq</th>
                                            <th class="text-center">내용</th>
                                            <th class="text-center">Push Type</th>
                                            <th class="text-center">Action</th>
                                            <th class="text-center">상태</th>
                                            <th class="text-center">등록 날짜</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rows as $val)
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="check_item[]" id="check_item" value="{{$val->id}}">
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="/admin/pushes/{{$val->id}}/edit">{{$val->id}}</a>
                                                    </td>
                                                    <td class="text-center">
                                                        {{$val->content}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$config['push_type'][$val->push_type]}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$config['action'][$val->action]}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$config['state'][$val->state]}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$val->created_at}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{--<div><h6>Total: {{$rows->total()}}</h6></div>--}}
                                    {{--<div class="container-fluid">--}}
                                        {{--<div id="contents">--}}
                                            {{--@foreach ($rows as $val)--}}
                                                {{--<div class="row py-2">--}}
                                                    {{--<div class="col-sm-1">--}}
                                                        {{--<input type="checkbox" name="check_item[]" id="check_item" value="{{$val->id}}">--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col-sm-1">--}}
                                                        {{--<a href="/push/form/{{$val->id}}">{{$val->id}}</a>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col-sm-4">--}}
                                                        {{--{{$val->contents}}--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col-sm-1">--}}
                                                        {{--{{$config['push_type'][$val->push_type]}}--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col-sm-2">--}}
                                                        {{--{{$config['action'][$val->action]}}--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col-sm-1">--}}
                                                        {{--{{$config['state'][$val->state]}}--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col-sm-2">--}}
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
    </main>
@stop

@push('script')
    <script>
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
                format: 'Y-m-d',
                timepicker: false
            });
        });
    </script>
@endpush
