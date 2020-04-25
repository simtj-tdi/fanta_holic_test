<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fanta</title>

    <!-- Bootstrap core CSS -->
    <link href="{{env('PUBLIC_PATH')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{env('PUBLIC_PATH')}}/css/starter-template.css" rel="stylesheet">
</head>
<body>

<main role="main" class="container">

    <h3><b><span data-feather="log-in"></span></b>  fanta_holic</h3>

    <form>
        <div class="form-group">
            <label for="exampleInputEmail1">ID</label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Enter id">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
        </div>
        <button type="button" class="btn btn-primary" id="btn_login">로그인</button>
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
        $('input').keydown(function(key){
            if(key.keyCode == 13) {
                $('#btn_login').trigger('click');
            }
        });
        $( "#btn_login" ).click(function() {
            $.ajax({
                url: '/admin/login',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { "email": $("#email").val(), "password": $("#password").val() },
                dataType: 'json',
                statusCode: {
                    422: function (data) {
                        if (data.responseJSON.error == 1) {
                            alert(data.responseJSON.message);
                        } else if (data.responseJSON.error == 2) {
                            alert(data.responseJSON.message);
                            location.replace('/admin/password/'+$("#email").val());
                        }
                    }
                },
                success: function (res) {
                    alert("Welcome!");
                    location.replace('/admin');
                },
                error: function (e) {
                    console.log('asdfsdf');
                    console.log(e);
                }
            });
        });
    });
</script>
</body>
</html>
