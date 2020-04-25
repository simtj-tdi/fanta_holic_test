<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Celeb Tube</title>

    <!-- Bootstrap core CSS -->
    <link href="{{env('PUBLIC_PATH')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{env('PUBLIC_PATH')}}/css/starter-template.css" rel="stylesheet">
</head>
<body>

<main role="main" class="container">

    <h3><b><span data-feather="log-in"></span> 패스워드 변경</b></h3>

    <form>
        <div class="form-group">
            <label for="exampleInputEmail1">ID</label>
            <input type="text" readonly class="form-control" name="email" id="email" value="{{$email}}">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">변경할 Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password 확인</label>
            <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="Password Confirm">
        </div>
        <div class="form-group">
            <span id='message'></span>
        </div>
        <button type="button" class="btn btn-primary" id="btn_password">등록</button>
    </form>

</main><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<script src="{{env('PUBLIC_PATH')}}/js/popper.min.js"></script>
<script src="{{env('PUBLIC_PATH')}}/js/bootstrap.min.js"></script>

<!-- Icons -->
<script src="{{env('PUBLIC_PATH')}}/js/feather.min.js"></script>
<script>
    feather.replace()
</script>

<script>
    $(function(){
        $('#password, #password_confirm').on('keyup', function () {
            if ($('#password').val() == $('#password_confirm').val()) {
                $('#message').html('');
            } else {
                $('#message').html('패스워드가 일치하지 않습니다.').css('color', 'red');
            }
        });

        $('input').keydown(function(key){
            if(key.keyCode == 13) {
                $('#btn_login').trigger('click');
            }
        });
        $( "#btn_password" ).click(function() {
            if ($('#password').val() != $('#password_confirm').val()) {
                alert('변경할 패스워드와 패스워드 확인이 일치하지 않습니다.');
            } else {
                $.ajax({
                    url: '/admin/password',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { "email": $("#email").val(), "password": $("#password").val() },
                    dataType: 'json',
                    statusCode: {
                        422: function (data) {
                            alert(data.responseJSON.message);
                        }
                    },
                    success: function (res) {
                        alert("패스워드가 변경 되었습니다.");
                        location.replace('/admin/login');
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
