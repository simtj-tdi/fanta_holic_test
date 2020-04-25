@extends('layouts.master')
@push('style')
    <style>
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
    </style>
@endpush
@section('content')
    <main class="main">
        <form method="GET" class='' action="{{url('admin/users')}}">
            {{--            <input type="hidden" name="user_type" value="{{$params['userType']}}">--}}


            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    APP MANAGEMENT
                </li>
                <li class="breadcrumb-item">팬 관리</li>
                <li class="breadcrumb-item active"><strong>유저 관리</strong></li>
            </ol>


            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="float-left mt-2">유저수 ( {{ $total }} )</div>
                                    <div class="float-right">
                                        {{--                                    <a href="{{route('board.new')}}" class="btn btn-primary">신규</a>--}}
                                        {{--<a href="/admin/campaigns/create" class="btn btn-success mb-2" role="button">새 게시물</a>--}}
                                        {{--                                    <button type="button" id="create_notice_button" class="btn btn-success mb-2">new</button>--}}
                                        {{--                                    <button id="delete_button" class="btn btn-danger mb-2" disabled>삭제(미완성)</button>--}}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group row" style="padding-left: 10px;">
                                                        <select class="form-control col-md-1" name="schDateType">
                                                            <option value="created_at" {{ $params['schDateType'] == 'created_at' ? 'selected' : '' }} >
                                                                등록일
                                                            </option>
                                                            <option value="updated_at" {{ $params['schDateType'] == 'updated_at' ? 'selected' : '' }}>
                                                                수정일
                                                            </option>
                                                        </select>
                                                        <input class="form-control col-md-2 datetimepicker ml-2 text-center"
                                                               autocomplete="off" type="text" placeholder="시작일"
                                                               name="startDate" value="{{ $params['startDate'] }}"/>
                                                        &nbsp;&nbsp;&nbsp; ~
                                                        <input class="form-control col-md-2 datetimepicker ml-2 text-center"
                                                               autocomplete="off" type="text" placeholder="종료일"
                                                               name="endDate" value="{{ $params['endDate']}}"/>
                                                        <button class="btn btn-primary ml-2 changeDateBtn" type="button"
                                                                startDate="{{date('Y-m-d')}}"
                                                                endDate="{{date('Y-m-d')}}">Today
                                                        </button>
                                                        <button class="btn btn-primary ml-2 changeDateBtn" type="button"
                                                                startDate="{{date('Y-m-d',strtotime(' -1 day'))}}"
                                                                endDate="{{date('Y-m-d')}}">전일
                                                        </button>
                                                        <button class="btn btn-primary ml-2 changeDateBtn" type="button"
                                                                startDate="{{date('Y-m-d',strtotime(' -3 day'))}}"
                                                                endDate="{{date('Y-m-d')}}">3일전
                                                        </button>
                                                        <button class="btn btn-primary ml-2 changeDateBtn" type="button"
                                                                startDate="{{date('Y-m-d',strtotime(' -7 day'))}}"
                                                                endDate="{{date('Y-m-d')}}">7일전
                                                        </button>
                                                        <button class="btn btn-primary ml-2 changeDateBtn" type="button"
                                                                startDate="{{date('Y-m-d',strtotime(' -30 day'))}}"
                                                                endDate="{{date('Y-m-d')}}">30일전
                                                        </button>
                                                    </div>
                                                    <div class="form-group row pl-2">
                                                        <select class="form-control col-md-1" name="schType">
                                                            <option value="">전체</option>
                                                            <option value="email" {{ $params['schType']  === "email" ? "selected" : ""}}>
                                                                이메일
                                                            </option>
                                                            <option value="name" {{ $params['schType']  === "name" ? "selected" : ""}}>
                                                                이름
                                                            </option>
                                                            <option value="nickname" {{ $params['schType']  === "nickname" ? "selected" : ""}}>
                                                                닉네임
                                                            </option>
                                                            <option value="mobile" {{ $params['schType']  === "mobile" ? "selected" : ""}}>
                                                                휴대폰
                                                            </option>
                                                        </select>
                                                        <input class="form-control col-md-4 ml-2" type="text"
                                                               name="schVal" value="" placeholder="검색어를 입력해주세요.">
                                                        <button type="submit" class="btn btn-success ml-2">검색</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <table class="table table-responsive-sm"
                                           style="text-align: center;vertical-align:middle;">
                                        <thead>
                                        <tr style="vertical-align: middle;">
                                            <th class="text-center">
                                                <input type="checkbox" class="" id="allCheckBox">
                                            </th>
                                            <th>유저 ID</th>
                                            <th>email</th>
                                            <th>닉 네임</th>
                                            <th>이름</th>
                                            <th>휴대폰</th>
                                            <th>생년월일</th>
                                            <th>
                                                <select class="form-control" name="userType"
                                                        onchange="this.form.submit()">
                                                    <option value="">전체</option>
                                                    <option value="user" {{ $params['userType'] === "user" ? "selected" : '' }}>
                                                        회원
                                                    </option>
                                                    <option value="none" {{ $params['userType'] === "none" ? "selected" : '' }} >
                                                        비회원
                                                    </option>
                                                </select>
                                            </th>
                                            <th>
                                                <select class="form-control" name="schBlack"
                                                        onchange="this.form.submit()">
                                                    <option value="">회원관리</option>
                                                    <option value="0">일반</option>
                                                    <option value="1">블랙</option>
                                                </select>
                                            </th>
                                            <th>수정일</th>
                                            <th>등록일</th>
                                            <th>관리</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($rows as $rowKey => $val)
                                            <tr style="height: 100px;vertical-align: middle;">
                                                <td class="text-center"><input type="checkbox"
                                                                               class="bbsCheck "
                                                                               name="check_item[]"
                                                                               id="{{$val->id}}" value="{{$val->id}}"/>
                                                </td>
                                                <td>{{ $val->id  }}</td>
                                                <td>{{ $val->email  }}</td>
                                                <td>{{ $val->nickname  }}</td>
                                                <td>{{ $val->name  }}</td>
                                                <td>{{ $val->mobile  }}</td>
                                                <td>{{ $val->birth  }}</td>
                                                <td>
                                                    {{ ($val->email || $val->nickname) ?"회원" : "비회원" }}
                                                </td>
                                                <td>
                                                    {{ ($val->black ) ?"블랙" : "일반" }}
                                                </td>
                                                <td>{{$val->updated_at}}</td>
                                                <td>{{$val->created_at}}</td>
                                                <td>
                                                    <a href="/admin/users/{{$val->id}}" class="btn btn-success">수정</a>
                                                    <button class="btn btn-danger delBtn" type="button"
                                                            id="{{$val->id}}"
                                                            onclick="console.log($(this).next())"> 탈퇴
                                                    </button>
                                                    {{--                                                <button type="button" class="btn btn-success">수정</button>--}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @if($rows->count())
                                        <div class="text-center">
                                            {!! $rows->render() !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });

            //날짜 버튼
            $('.changeDateBtn').click(function () {
                let startDate = $(this).attr('startDate');
                let endDate = $(this).attr('endDate');
                $('input[name=startDate]').val(startDate);
                $('input[name=endDate]').val(endDate);

                $(this.form)[0].submit();
            });

            $('.delBtn').click(function () {
                if (!confirm('정말 탈퇴 시키겠습니까?')) {
                    return false;
                }
                let id = $(this).attr('id');
                $.ajax(
                    {
                        url: '/admin/users/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{@csrf_token()}}',
                        },
                    }
                ).done(function (response) {
                    console.log(response);
                    alert('삭제 되었습니다');
                    location.reload();
                }).catch(function () {
                })
            });
        });
    </script>
@endpush
