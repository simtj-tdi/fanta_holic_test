@extends('layouts.master')

@section('content')
    <form method="POST" action="{{url('/register')}}">
        {{ csrf_field() }}
        <div class="form-group row">
            <label for="app" class="col-sm-2 col-form-label">앱</label>
            <div class="col-sm-10">
                <select class="form-control" name="app" id="app">
                    <option value="all">전체</option>
                    <option value="leeseol" selected>이설튜브</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">이름</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">계정</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="email" name="email" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">패스워드</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="level" class="col-sm-2 col-form-label">권한</label>
            <div class="col-sm-10">
                <select class="form-control" name="level" id="level">
                    <option value="0">Master</option>
                    <option value="1">Manager</option>
                    <option value="2">Uploader</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="btn_register">등록</button>
    </form>

    <hr/>
    <div><h6>Total: {{$rows->total()}}</h6></div>
    <div class="container-fluid">
        <div class="row grid-striped py-3">
            <div class="col-sm-1">
                <h6 class="font-weight-bold">seq</h6>
            </div>
            <div class="col-sm-2">
                <h6 class="font-weight-bold">앱</h6>
            </div>
            <div class="col-sm-2">
                <h6 class="font-weight-bold">이름</h6>
            </div>
            <div class="col-sm-2">
                <h6 class="font-weight-bold">아이디</h6>
            </div>
            <div class="col-sm-2">
                <h6 class="font-weight-bold">권한</h6>
            </div>
            <div class="col-sm-2">
                <h6 class="font-weight-bold">등록 날짜</h6>
            </div>
        </div>

        <div id="contents">
            @foreach ($rows as $val)
                <div class="row py-2">
                    <div class="col-sm-1">
                        {{$val->id}}
                    </div>
                    <div class="col-sm-2">
                        {{$value['app'][$val->app]}}
                    </div>
                    <div class="col-sm-2">
                        {{$val->name}}
                    </div>
                    <div class="col-sm-2">
                        {{$val->email}}
                    </div>
                    <div class="col-sm-2">
                        {{$value['level'][$val->level]}}
                    </div>
                    <div class="col-sm-2">
                        {{$val->created_at}}
                    </div>
                </div>
                <hr/>
            @endforeach
        </div>
    </div>

    {{ $rows->links() }}
@stop

@push('script')
    <script>
        $(function(){
            $("#btn_register").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: '/register',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "app": $("#app").val(),
                        "name": $("#name").val(),
                        "email": $("#email").val(),
                        "password": $("#password").val(),
                        "level": $("#level").val()
                    },
                    dataType: 'json',
                    statusCode: {
                        422: function (data) {
                            alert(data.responseJSON.message);
                        }
                    },
                    success: function (res) {
                        alert("신규 사용자가 등록 되었습니다.");
                        location.replace('/register');
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            });
        });
    </script>
@endpush
