


$(document).ready(function(){
    var msnry =$('.grid').masonry({
        // options
        itemSelector: '.grid-item',
        columnWidth: 240,
        // isAnimated: !Modernizr.csstransitions
    });
    // $('#stv_list').addClass('invisible');
    // $('#stv_list').removeClass('visible');
    $('#columns').imagesLoaded().progress(function () {
        $('#columns').masonry('layout');
    });
    // datetime picker
    $.datetimepicker.setLocale('ko');
    $('.datetimepicker').datetimepicker({
        format: 'Y-m-d',
        timepicker: false
    });

    //스크롤메뉴
    var top = $(window).scrollTop(); // 현재 스크롤바의 위치값을 반환합니다.

    /*사용자 설정 값 시작*/
    var speed          = 1;     // 따라다닐 속도 : "slow", "normal", or "fast" or numeric(단위:msec)
    var easing         = 'linear'; // 따라다니는 방법 기본 두가지 linear, swing
    var $layer         = $('#menu_bar'); // 레이어 셀렉팅
    var layerTopOffset = 0;   // 레이어 높이 상한선, 단위:px
// 스크롤 바를 내린 상태에서 리프레시 했을 경우를 위해
    if (top > 0 )
        $(window).scrollTop(layerTopOffset+top);
    else
        $(window).scrollTop(0);
    $(window).scroll(function(){

        yPosition = $(window).scrollTop()-300;
        if (yPosition < 0)
        {
            $('#menu_bar').addClass('invisible');
            $('#menu_bar').removeClass('visible');
        }else{
            $('#menu_bar').removeClass('invisible');
            $('#menu_bar').addClass('visible');
        };

        yPosition = 55;
        $layer.animate({"top":yPosition }, {duration:speed, easing:easing, queue:false});
    });

    $('.sidebar-toggler').on('click',function(){
        setTimeout(function() {
            $('.grid').masonry('layout');
        }, 300);

    });
    $('.aside-menu-toggler').on('click',function(){
        setTimeout(function() {
            $('.grid').masonry('layout');
        }, 300);

    });

});
//@end 2019.01.25 cch alert창 form 수정



// $(document).keyup(function(e) {
//     if (e.keyCode == 27) { // escape key maps to keycode `27`
//         e.preventDefault();
//         alert('dd');
//         modify_chk_array=Array();
//     }
// });