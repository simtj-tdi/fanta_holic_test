@extends('layouts.master')

@push('style')
    <style>
        .table td {
            vertical-align: middle;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <main class="main">
        <form action="{{route('board.index')}}" method="get">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin">홈</a>
                </li>
                <li class="breadcrumb-item">게시물 관리</li>
                <li class="breadcrumb-item active"><strong>전체</strong></li>
            </ol>
            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="float-left mt-2">전체 게시물 ( {{$total}} )</div>
                                    <div class="float-right">
                                        <a href="{{route('board.new')}}" class="btn btn-primary">신규</a>
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
                                                    </div>
                                                    <div class="form-group row pl-2">
                                                        <select class="form-control col-md-1" name="schType">
                                                            <option value="">전체</option>
                                                            <option value="title">제목</option>
                                                            <option value="content">내용</option>
                                                        </select>
                                                        <input class="form-control col-md-4 ml-2" type="text"
                                                               name="schVal" value="" placeholder="검색어를 입력해주세요.">
                                                        <button type="submit" class="btn btn-success ml-2">검색</button>
                                                    </div>
                                                    <div class="form-group row col-md-2 pl-2 d-none" id="actionBox">
                                                        <div class="bg-dark p-2 rounded-10">
                                                            <button type="button" class="btn btn-success bulkUpdate"
                                                                    state="1">게시
                                                            </button>
                                                            <button type="button" class="btn btn-warning bulkUpdate"
                                                                    state="2">미
                                                                게시
                                                            </button>
                                                            <button type="button" class="btn btn-danger bulkDelete"
                                                                    state="del">
                                                                삭제
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <table class="table table-responsive-sm"
                                           style="text-align: center;vertical-align:middle;">
                                        @php
                                            $stateConfig = [ '0' => '미검수', '1'=>'게시' ,'2'=>'미게시' ];
                                            $channelConfig = ['event' => '이벤트', 'fanfeed' => 'FanFeed','instagram' => '인스타그램',
                                               'myfeed' => '마이피드','news' => '뉴스','twitter' => '트위터','vlive' => '브이라이브','youtube'=> '유튜브'];
                                        @endphp
                                        <thead>
                                        <tr style="vertical-align: middle;">
                                            <th class="text-center"><input type="checkbox" class="" id="allCheckBox">
                                            </th>
                                            <th>게시물 ID</th>
                                            <th>이미지</th>
                                            <th>
                                                <select class="form-control" name="schChannel"
                                                        onchange="this.form.submit()">
                                                    <option value="">매체</option>
                                                    @foreach( $channelConfig as $channelKey => $channelVal)
                                                        <option value="{{$channelKey}}" {{ $params['schChannel'] == $channelKey ? 'selected' : '' }} >{{$channelVal}}</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th>
                                                <select class="form-control" name="schState">
                                                    <option value="">상태</option>
                                                    @foreach( $stateConfig as $stateKey => $stateVal)
                                                        <option value="{{$stateKey}}" {{ ($params['schState'] == $stateKey && $params['schState'] != null) ? 'selected' : '' }}>{{$stateVal}}</option>
                                                    @endforeach
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
                                                <td><img class="thumbnailImgBox"
                                                         style="width:250px;height: 100px;max-width: 640px;max-height: 355px;"
                                                         src="{{env('CDN_URL').$val->thumbnail_url}}"
                                                         value="{{$val->id}}">
                                                </td>
                                                <td>{{$channelConfig[$val->type]}}</td>
                                                <td>
                                                    <select name="" class="form-control stateChange" id="{{$val->id}}">
                                                        @foreach( $stateConfig as $stateKey => $stateVal)
                                                            <option value="{{$stateKey}}" {{ $stateKey == 0 ? 'disabled' : '' }}  {{ $val->state == $stateKey ? 'selected' : '' }} >{{$stateVal}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>{{$val->updated_at}}</td>
                                                <td>{{$val->created_at}}</td>
                                                <td>
                                                    <a href="/admin/boards/{{$val->id}}" class="btn btn-success">수정</a>
                                                    <button class="btn btn-danger delBtn" type="button"
                                                            id="{{$val->id}}"
                                                            onclick="console.log($(this).next())"> 삭제
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
        <form id="deleteFrm" method="post" action="{{route('board.index')}}">
            @method('delete')
            @csrf
        </form>
    </main>
@endsection

@push('script')
    {{--<script src="/js/app.js"></script>--}}
    {{--    <script src="/js/board_control.js"></script>--}}
    {{--    <script src="{{env('PUBLIC_PATH')}}/js/boardcontrol/search_list_search.js?version=0.5.1"></script>--}}
    <script type="text/javascript">
        $(document).ready(function () {

            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });

            $('#allCheckBox').click(function () {
                let checked = $(this).is(':checked');
                $('.bbsCheck').prop('checked', checked);
                $.checkLengthBox();
            });

            var test = [];

            console.log(test);

            $('.bbsCheck').click(function () {
                $.checkLengthBox();
            });

            $.checkLengthBox = function () {
                if ($('.bbsCheck:checked').length > 0) {
                    $('#actionBox').removeClass('d-none');
                    $('#actionBox').slideDown(200);
                } else {
                    $('#actionBox').slideUp(200);
                    $('#actionBox').addClass('d-none');

                }
            }
            //썸네일 이미지
            $('.thumbnailImgBox').click(function () {
                if ($(this).width() === 250 && $(this).height() === 100) {
                    $(this).css('width', 'auto');
                    $(this).css('height', 'auto');
                } else {
                    $(this).css('width', '250px');
                    $(this).css('height', '100px');
                }
            });

            $('.delBtn').click(function () {
                if (!confirm('정말 삭제하시겠습니까?')) {
                    return false;
                }
                let id = $(this).attr('id');
                console.log(id);
                $.ajax(
                    {
                        url: '/admin/boards/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{@csrf_token()}}',
                        },
                    }
                ).done(function (response) {
                    console.log(response);
                    location.reload();
                }).catch(function () {
                })
            });

            $('.bulkUpdate').click(function () {
                let chkCnt = $('.bbsCheck:checked').length;
                let state = $(this).attr('state');
                if (!confirm(chkCnt + ' 개를 변경하시겠습니까?')) {
                    return false;
                }

                let requestList = [];
                $('.bbsCheck:checked').each((key, checkbox) => {
                    let id = $(checkbox).attr('id');
                    let obj = {id, state};
                    requestList.push(obj);
                });

                $.ajax(
                    {
                        url: '/admin/boards/',
                        type: 'patch',
                        dataType: "json",
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{@csrf_token()}}',
                        },
                        data: JSON.stringify(requestList),
                        success: function (response) {

                            console.log(response);
                            if (response.rst === true) {
                                if (stateUpdate(id, state)) {
                                    alert('상태가 변경되었습니다');
                                }
                            }
                        },
                    }
                );
            });

            $('.bulkDelete').click(function () {
                let chkCnt = $('.bbsCheck:checked').length;
                let state = $(this).attr('state');
                if (!confirm(chkCnt + ' 개를 삭제 하시겠습니까?')) {
                    return false;
                }

                let requestList = [];
                $('.bbsCheck:checked').each((key, checkbox) => {
                    let id = $(checkbox).attr('id');
                    let obj = {id, state};
                    requestList.push(obj);
                });

                $.ajax(
                    {
                        url: '/admin/boards/',
                        type: 'patch',
                        dataType: "json",
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{@csrf_token()}}',
                        },
                        data: JSON.stringify(requestList),
                        success: function (response) {

                            console.log(response);
                            if (response.rst === true) {
                                if (stateUpdate(id, state)) {
                                    alert('삭제되었습니다.');
                                }
                            }
                        },
                    }
                );
            });

            function stateUpdate(id, state) {
                let org_url = '/admin/boards/';
                let url = org_url + id;

                $.ajax(
                    {
                        url: url,
                        type: 'PUT',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{@csrf_token()}}',
                        },
                        data: {state: state},
                        success: function (response) {
                            if (response.rst === true) {
                                return true;
                            }
                        },
                    }
                ).done(function () {

                }).catch(function () {

                })
            }

            function stateDelete(id) {
                let org_url = '/admin/boards/';
                let url = org_url + id;

                $.ajax(
                    {
                        url: url,
                        type: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{@csrf_token()}}',
                        },
                        // data: {state: state},
                        success: function (response) {

                        },
                    }
                ).done(function () {

                }).catch(function () {

                })
            }

            $('.stateChange').change(function () {
                let id = $(this).attr('id');
                let state = $(this).val();

                console.log('id!!', id);
                if (stateUpdate(id, state)) {
                    alert('상태가 변경되었습니다');
                }
                console.log(id, state);
            })

            //날짜 버튼
            $('.changeDateBtn').click(function () {
                let startDate = $(this).attr('startDate');
                let endDate = $(this).attr('endDate');
                $('input[name=startDate]').val(startDate);
                $('input[name=endDate]').val(endDate);

                $(this.form)[0].submit();
            });
        });
        //php 변수 자바스크립트로 넘기는 코드 js파일로 뜯어노면 오류나서 남겨둠
        var token = "{{csrf_token()}}";
    </script>
@endpush
