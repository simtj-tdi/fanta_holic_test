//스크롤메뉴
var top = $(window).scrollTop(); // 현재 스크롤바의 위치값을 반환합니다.

/*사용자 설정 값 시작*/
var speed          = 1;     // 따라다닐 속도 : "slow", "normal", or "fast" or numeric(단위:msec)
var easing         = 'linear'; // 따라다니는 방법 기본 두가지 linear, swing
var $layer         = $('#stv_list'); // 레이어 셀렉팅
var layerTopOffset = 0;   // 레이어 높이 상한선, 단위:px
// 스크롤 바를 내린 상태에서 리프레시 했을 경우를 위해
if (top > 0 )
    $(window).scrollTop(layerTopOffset+top);
else
    $(window).scrollTop(0);

$(document).ready(function(){
    $('#stv_list').addClass('invisible');
    $('#stv_list').removeClass('visible');
});
//스크롤이벤트가 발생하면
$(window).scroll(function(){
    yPosition = $(window).scrollTop()-300;
    if (yPosition < 0)
    {
        $('#stv_list').addClass('invisible');
        $('#stv_list').removeClass('visible');
    }else{
        $('#stv_list').removeClass('invisible');
        $('#stv_list').addClass('visible');
    };

    yPosition = 48;

    $layer.animate({"top":yPosition }, {duration:speed, easing:easing, queue:false});
});

// 2019.2.12 cch 태그자동완성시 엔터키 submit 방지용
$('input[type="text"]').keydown(function() {
    if (event.keyCode === 13) {
        event.preventDefault();
    }
});