@extends('layouts.master')

@section('content')
    <main class="main">

        <form method="POST" action="{{url('/admin/users/'.$user->id)}}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="put">
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
                        <div class="col-md-6">
                            <div class="card">
                                @if (Session::has('message'))
                                    <div class="alert alert-danger font-weight-bold">{{ Session::get('message') }}</div>
                                @endif
                                <div class="card-header">
                                    <div class="float-left mt-2"><b>유저 관리</b></div>
                                    <div class="float-right"></div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    이메일</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="id"
                                                           name="email"
                                                           value="{{ $user->email?? ''}}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    닉네임</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control " id="id"
                                                           name="nickname"
                                                           value="{{$user->nickname?? ''}}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    이름</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="id"
                                                           name="name"
                                                           value="{{$user->name?? ''}}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    휴대폰 번호</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control " id="id"
                                                           name="mobile"
                                                           value="{{$user->mobile?? ''}}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    성별</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control " id="id"
                                                           name="mobile"
                                                           value="{{$user->gender?'남성' : '여성'}}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    보유 하트갯수</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control " id="id"
                                                           name="mobile"
                                                           value="{{$user->item_count?? ''}}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    SNS 타입
                                                </label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" name="black" id="black" readonly="readonly" disabled>
                                                        @foreach( \App\Enums\UserSnsType::toSelectArray() as $typeKey => $typeDesc)
                                                            <option value="{{$typeKey}}" {{$typeKey === $user->sns_type->value ?"selected" :""}}>{{$typeDesc}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @if($user->sns_type->value !== \App\Enums\UserSnsType::SNS_NORMAL)
                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    SNS ID
                                                </label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control " id="id"
                                                           name="mobile"
                                                           value="{{$user->sns_id?? ''}}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="form-group row">
                                                <label for="black"
                                                       class="col-sm-2 col-form-label text-center">블랙리스트</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" name="black" id="black">
                                                        <option value="0" @if (isset($user->black)) {{($user->black=='0')? 'selected' : ''}} @endif>
                                                            일반
                                                        </option>
                                                        <option value="1" @if (isset($user->black)) {{($user->black=='1')? 'selected' : ''}} @endif>
                                                            차단
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    변경일</label>
                                                <div class="col-sm-10 ">
                                                    <p class="form-control-static">{{$user->updated_at?? ''}}</p>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="id" class="col-sm-2 col-form-label text-center">
                                                    등록일</label>
                                                <div class="col-sm-10">
                                                    <p class="form-control-static">{{$user->created_at?? ''}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-success">수정</button>
                                    <a href="{{route('users.index')}}" class="btn btn-primary">리스트</a>
                                    <button type="button" class="btn btn-danger delBtn" id="{{$user->id}}">탈퇴</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">

                            <div class="nav-tabs-boxed">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                            href="#device-box"
                                                            role="tab" aria-controls="device">기기</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                            href="#login-box"
                                                            role="tab" aria-controls="login">로그인</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#item-box"
                                                            role="tab" aria-controls="item">아이템</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#board-box"
                                                            role="tab" aria-controls="board">게시글</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#comment-box"
                                                            role="tab" aria-controls="comment">댓글</a></li>
{{--                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#music-box"--}}
{{--                                                            role="tab" aria-controls="music">음악</a></li>--}}
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="device-box" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label for="black"
                                                               class="col-sm-2 col-form-label text-center">기기</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" name="device">
                                                                @php $i = 1; @endphp
                                                                @foreach($user->devices as $device)
                                                                    <option value="{{$device->id}}">[{{$i++}}
                                                                        ] {{$device->device}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="app_version"
                                                               class="col-sm-2 col-form-label text-center">앱
                                                            버전</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" name="app_ver" readonly
                                                                    disabled>
                                                                @php $i = 1; @endphp
                                                                @foreach($user->devices as $device)
                                                                    <option value="{{$device->id}}" readonly>[{{$i++}}
                                                                        ] {{$device->app_version}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="store_type"
                                                               class="col-sm-2 col-form-label text-center">OS
                                                            타입</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" name="store_type" readonly
                                                                    disabled>
                                                                @php $i = 1; @endphp
                                                                @foreach($user->devices as $device)
                                                                    <option value="{{$device->id}}" readonly>[{{$i++}}
                                                                        ] {{$device->store_type}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="os_version"
                                                               class="col-sm-2 col-form-label text-center">OS
                                                            버전</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" name="os_version" readonly
                                                                    disabled>
                                                                @php $i = 1; @endphp
                                                                @foreach($user->devices as $device)
                                                                    <option value="{{$device->id}}" readonly>[{{$i++}}
                                                                        ] {{$device->os_version}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="ad_id" class="col-sm-2 col-form-label text-center">기기
                                                            광고
                                                            ID</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" name="device_key" readonly
                                                                    disabled>
                                                                @php $i = 1; @endphp
                                                                @foreach($user->devices as $device)
                                                                    <option value="{{$device->id}}" readonly>[{{$i++}}
                                                                        ] {{$device->device_key}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="login-box" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <table class="table table-responsive-sm">
                                                        <thead>
                                                        <tr>
                                                            <th>접속 시간</th>
                                                            <th>IP</th>
                                                            <th>기기</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach( $user->userLoginHistory as $historyKey => $historyVal)
                                                            <tr>
                                                                <td>{{$historyVal->created_at->timezone('Asia/Seoul')}}</td>
                                                                <td>{{$historyVal->ip}}</td>
                                                                <td>{{$historyVal->device}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="item-box" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <table class="table table-responsive-sm">
                                                        <thead>
                                                        <tr>
                                                            <th>사용/충전</th>
                                                            <th>사용 시간</th>
                                                            <th>하트 갯수</th>
                                                            <th>타입</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach( $user->userItem as $itemKey => $itemVal)
                                                            <tr>
                                                                <td>{{ in_array($itemVal->log_type,['B']) ? "사용" :"충전" }}</td>
                                                                <td>{{$itemVal->created_at->timezone('Asia/Seoul')}}</td>
                                                                <td>{{$itemVal->item_count}}</td>
                                                                <td>{{$itemVal->log_type->description}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="board-box" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <table class="table table-responsive-sm">
                                                        <thead>
                                                        <tr>
                                                            <th>등록일</th>
                                                            <th>제목</th>
                                                            <th>이미지</th>
                                                            <th>보기</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach( $user->board as $boardKey => $boardVal)
                                                            <tr>
                                                                <td>{{$boardVal->created_at->timezone('Asia/Seoul')}}</td>
                                                                <td>{{$boardVal->title}}</td>
                                                                <td>{{ $boardVal->state ? "게시":"미게시"}}</td>
                                                                <td><a class="btn btn-success"
                                                                       target="_blank"
                                                                       href="{{route('board.show',['id'=>$boardVal->id])}}">
                                                                        보기</a></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="comment-box" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <table class="table table-responsive-sm">
                                                        <thead>
                                                        <tr>
                                                            <th>등록일</th>
                                                            <th>코멘트</th>
                                                            <th>게시글 보기</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach( $user->comment as $key => $val)
                                                            <tr>
                                                                <td>{{$val->created_at->timezone('Asia/Seoul')}}</td>
                                                                <td>{{$val->comment}}</td>
                                                                <td><a class="btn btn-success"
                                                                       target="_blank"
                                                                       href="{{route('board.show',['id'=>$val->board_id])}}">
                                                                        보기</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="music-box" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <table class="table table-responsive-sm">
                                                        <thead>
                                                        <tr>
                                                            <th>등록일</th>
                                                            <th>제목</th>
                                                            <th>이미지</th>
                                                            <th>보기</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach( $user->board as $boardKey => $boardVal)
                                                            <tr>
                                                                <td>{{$boardVal->created_at->timezone('Asia/Seoul')}}</td>
                                                                <td>{{$boardVal->title}}</td>
                                                                <td>{{ $boardVal->state ? "게시":"미게시"}}</td>
                                                                <td><a class="btn btn-success"
                                                                       target="_blank"
                                                                       href="{{route('board.show',['id'=>$boardVal->id])}}">
                                                                        보기</a></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </main>
@stop

@push('script')
    <script>
        $(document).ready(function () {

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

            // datetime picker
            $.datetimepicker.setLocale('ko');
            $('.datetimepicker').datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });
            console.log($('[name="device"]').val());
            $('[name="device"]').on('change', function (e) {
                e.preventDefault();
                $('[name="app_ver"]').val($('[name="device"]').val());
                $('[name="store_type"]').val($('[name="device"]').val());
                $('[name="os_version"]').val($('[name="device"]').val());
                $('[name="device_key"]').val($('[name="device"]').val());
            });
        });
    </script>
@endpush
