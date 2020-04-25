<!doctype html>
<html lang="kr">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Celeb Tube">
    <meta name="author" content="Celeb Tube">
    <meta name="keyword" content="">
    <title>Fan.Ta</title>
    <link href="/css/app.css?version={{ now()->format('YmdH') }}" rel="stylesheet">
    <link href="/css/admin.css?version={{ now()->format('YmdH') }}" rel="stylesheet">
    <link href="/css/style.css?version={{ now()->format('YmdH') }}" rel="stylesheet">
    {{--<link href="{{env('PUBLIC_PATH')}}/css/custom.css" rel="stylesheet">--}}
    @stack('style')
</head>

{{--<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show ">--}}
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show pace-done">
<div class="pace pace-inactive">
    <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
        <div class="pace-progress-inner"></div>
    </div>
    <div class="pace-activity"></div>
</div>
    @include('layouts.header')
    <div class="app-body">
            @include('layouts.menu')
            @yield('content')
    </div>
    @include('layouts.footer')
<script src="{{env('PUBLIC_PATH')}}/js/vue.js"></script>
<script src="/js/app.js?version={{ now()->format('YmdH') }}"></script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{env('PUBLIC_PATH')}}/js/jquery-3.3.1.min.js"></script>
<script src="{{env('PUBLIC_PATH')}}/js/jquery-ui.js"></script>
<script src="{{env('PUBLIC_PATH')}}/js/masonry.pkgd.min.js"></script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
<script src="{{env('PUBLIC_PATH')}}/js/popper.min.js"></script>
<script src="{{env('PUBLIC_PATH')}}/js/bootstrap.min.js"></script>
<script src="/js/admin.js?version={{ now()->format('YmdH') }}"></script>
<script src="{{env('PUBLIC_PATH')}}/js/jquery.datetimepicker.full.min.js"></script>



<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<!-- jQuery first, then Popper.js, Bootstrap, then CoreUI  -->

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper0.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>--}}
{{--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>--}}
{{--<script src="{{env('PUBLIC_PATH')}}/js/coreui.min.js"></script>--}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{env('PUBLIC_PATH')}}/js/ie10-viewport-bug-workaround.js"></script>

<!-- Icons -->
<script src="{{env('PUBLIC_PATH')}}/js/feather.min.js"></script>

{{--<script src="/js/app.js?version={{ now()->format('YmdH') }}"></script>--}}
{{--<script src="/js/admin.js?version={{ now()->format('YmdH') }}"></script>--}}
{{--<script src="/js/app.js?version={{ now()->format('YmdH') }}"></script>--}}
{{--<script src="/js/admin.js?version={{ now()->format('YmdH') }}"></script>--}}
<!-- Mobile Side Menu Toggle -->
<script>
    feather.replace();
    $(function(){
        $('.ct-menu-toggle').on('click', function(){
            if(!$('.ct-nav').hasClass('on')){
                $('.ct-nav').addClass('on')
            } else {
                $('.ct-nav').removeClass('on')
            }
        })

        $('body').on('click', '.aside-menu .pagination a', function(e) {
            e.preventDefault();

            $('#load a').css('color', '#dfecf6');
            $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="{{env('PUBLIC_PATH')}}/images/loading_spinner.gif" />');

            var url = $(this).attr('href');
            getArticles(url);
            window.history.pushState("", "", url);
        });

        function getArticles(url) {
            $.ajax({
                url : url
            }).done(function (data) {
                $('.logs').html(data);
            }).fail(function () {
                alert('Articles could not be loaded.');
            });
        }
    });
</script>
@stack('script')

</body>
</html>
