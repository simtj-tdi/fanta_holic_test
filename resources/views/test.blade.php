<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
1
<div id="check1" style="width: 100%"></div>
2
<div id="check2" style="width: 100%"></div>
3
<div id="check3" style="width: 100%"></div>
4
<div id="check4" style="width: 100%"></div>
5
<div id="check5" style="width: 100%"></div>
6
<div id="check6" style="width: 100%"></div>
7
<div id="check7" style="width: 100%"></div>
</body>

<script src="{{env('PUBLIC_PATH')}}/js/jquery-3.3.1.min.js"></script>
<script>
    var userAgent = navigator.userAgent;
    var visitedAt = ( new Date() ).getTime();

    $('#check1').text(userAgent);
    // $(document).ready(function(){
        $('#check2').text(userAgent.match(/Android/));
        if (userAgent.match(/Android/)) {
            $('#check3').text(userAgent.match(/Chrome/));
            if (userAgent.match(/Chrome/)) {
                // 안드로이드의 크롬에서는 intent만 동작하기 때문에 intent로 호출해야함
                setTimeout(function() {
                    // $('#check4').text("intent://fanta#Intent; scheme=스킴; action=..;category=..; package=com.celeb.tube.krieshachu; end;");
                    // location.href = "intent://fanta#Intent; scheme=스킴; action=..;category=..; package=com.celeb.tube.krieshachu; end;";
                    $('#check4').text("intent://fanta#Intent; scheme=스킴; action=..;category=..; package=com.dailymotion.dailymotion; end;");
                    location.href = "intent://fanta#Intent; scheme=스킴; action=..;category=..; package=com.dailymotion.dailymotion; end;";
                }, 1000);
            } else {
                // 크롬 이외의 브라우저들
                setTimeout(
                    function() {
                        if ((new Date()).getTime() - visitedAt < 2000) {
                            // $('#check5').text("https://play.google.com/store/apps/details?id=com.celeb.tube.krieshachu");
                            // location.href = "https://play.google.com/store/apps/details?id=com.celeb.tube.krieshachu";
                            $('#check5').text("https://play.google.com/store/apps/details?id=com.dailymotion.dailymotion");
                            location.href = "https://play.google.com/store/apps/details?id=com.dailymotion.dailymotion";
                        }
                    }, 500);

                var iframe = document.createElement('iframe');
                iframe.style.visibility = 'hidden';
                iframe.src = 'intent://fanta';
                document.body.appendChild(iframe);
                document.body.removeChild(iframe); // back 호출시 캐싱될 수 있으므로 제거
            }
        }
    // });
</script>
</html>