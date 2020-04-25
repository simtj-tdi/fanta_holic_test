


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
//search vue 인스턴스 태그검색 자동완성
var tag_list_search = new Vue({
    el:'#search',
    name:'search',
    data:{
        newTag:'',
    },
    components:{
        search_autocomplete: Autocomplete_search
    }
});
//2019.01.28 cch 전체 선택버튼
$("[name='check_all']").click(function(){
    if($("[name='check_all']").val()==0){
        $("[name='check_all']").val(1);
        $("[name='check_item[]']").prop("checked",true);
    }else{
        $("[name='check_all']").val(0);
        $("[name='check_item[]']").prop("checked",false);
    }
});
//2019.01.28 cch 전체 선택버튼

//전체컨트롤매뉴 수정 클릭시
//2019.01.28 cch 태그수정 alert


$("[name='btn_move_to_man']").on("click", function(e){
    e.preventDefault();
    var chk_len = $('[name="check_item[]"]:checked').length;
    if (chk_len > 0) {
        $('[name="_method"]').val('put');
        $('[name="change_gender"]').val(1);
        $('#form_'+title).attr('action', "/admin/boards/bulk/gender?type="+title).submit();
    } else {
        alert('게시물을 하나 이상 선택해 주세요');
        return false;
    }
});

$("[name='btn_move_to_woman']").on("click", function(e){
    e.preventDefault();
    var chk_len = $('[name="check_item[]"]:checked').length;
    if (chk_len > 0) {
        $('[name="_method"]').val('put');
        $('[name="change_gender"]').val(2);
        $('#form_'+title).attr('action', "/admin/boards/bulk/gender?type="+title).submit();
    } else {
        alert('게시물을 하나 이상 선택해 주세요');
        return false;
    }
});


$("[name='btn_open']").on("click", function(e){
    e.preventDefault();
    var send_cnt = 0;
    var chk_array = Array();
    var chkbox = $(".custom-control-input");
    for(var i=0;i<chkbox.length;i++){
        if (chkbox[i].checked == true) {
            chk_array[send_cnt] = chkbox[i].value;
            send_cnt++;
        }
    }

    if (send_cnt > 0) {
        $.ajax({
            url:"/admin/boards/bulk/tag/common?type="+title,
            method:"get",
            data :{
                'check_item':chk_array
            },
            dataType:"json",
        })
            .done(function(json){

                tag_list_open.tags=[];
                for(var i  in json.result){
                    if(json.result[i] != null && json.result[i] != ''){
                        tag_list_open.tags.push(json.result[i]);
                    }
                }
                $('#common_tags').val(json.common_tags);
                open_dialog.dialog( "open" );
            });
    } else {
        alert('게시물을 하나 이상 선택해 주세요');
        return false;
    }
});

//2019.01.25 cch alert창 form 수정
$("#btn_real_open").on('click',function(e){
    e.preventDefault();
    var str =tag_list_open.tags.join();
    $('#send_tag').val(str);
    if ($('#app_review').is(":checked"))
    {
        $('[name="app_review"]').val(1);
    }
    $('[name="_method"]').val('put');
    $('[name="change_state"]').val(1);
    $('#form_'+title).attr('action', "/admin/boards/bulk/open?type="+title).submit();
});

$("[name='btn_close']").on("click",function(e){
    e.preventDefault();
    var chk_len = $('[name="check_item[]"]:checked').length;
    if (chk_len > 0) {
        $('[name="app_review"]').html('');
        $('[name="_method"]').val('put');
        $('[name="change_state"]').val(2);
        $('#form_'+title).attr('action',"/admin/boards/bulk/open?type="+title).submit();
    } else {
        alert('게시물을 하나 이상 선택해 주세요');
        return false;
    }
});

$("[name='btn_modify_tag']").on('click',function(e){
    e.preventDefault();

    var send_cnt = 0;
    var chk_array = Array();
    var chkbox =$("[name='check_item[]']");
    for(var i=0;i<chkbox.length;i++){
        if (chkbox[i].checked == true) {
            chk_array[send_cnt] = chkbox[i].value;
            send_cnt++;
        }
    }
    if (send_cnt > 0) {
        $.ajax({
            url:"/admin/boards/bulk/tag/common?type="+title,
            method:"get",
            data :{
                'check_item':chk_array
            },
            dataType:"json",
        })
            .done(function(json){

                tag_list_modify.tags=[];
                for(var i  in json.result){
                    if(json.result[i] != null && json.result[i] != ''){
                        tag_list_modify.tags.push(json.result[i]);
                    }
                }
                tag_list_modify.ori_tags=[];
                if (send_cnt==1){
                    for(var i in json.ori_tags){
                        if(json.ori_tags[i] != null && json.ori_tags[i] != ''){
                            tag_list_modify.ori_tags.push(json.ori_tags[i]);
                        }
                    }
                }
                $('#common_tags').val(json.common_tags);
                modify_dialog.dialog( "open" );
            });
    } else {
        alert('게시물을 하나 이상 선택해 주세요');
        return false;
    }
});
//@end 2019.01.28 cch 태그수정 alert

//2019.01.28 cch 태그수정
//#(Tag)수정 버튼
$("#btn_modify_tags").on('click',function(e){
    e.preventDefault();
    var str =tag_list_modify.tags.join();
    var send_cnt = 0;
    var chk_array = Array();
    var chkbox = $(".custom-control-input");
    for(var i=0;i<chkbox.length;i++){
        if (chkbox[i].checked == true) {
            chk_array[send_cnt] = chkbox[i].value;
            send_cnt++;
        }
    }
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    $('#send_tag').val(str);
    console.log('#form_'+title);
    $('[name="_method"]').val('put');
    $('#form_'+title).attr('action', "/admin/boards/bulk/tag").submit();
});
//@end 2019.01.28 cch 태그수정

//text check 버튼
$("[name='btn_text_check']").on('click',function (e) {
    e.preventDefault();
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    var chk_len = $('[name="check_item[]"]:checked').length;
    if (chk_len > 0) {
        $('[name="_method"]').val('put');
        $('#form_'+title).attr('action', "/admin/boards/bulk/text?type="+title).submit();
    } else {
        $('#text_check_loading').removeClass('visible');
        $('#text_check_loading').addClass('invisible');
        alert('게시물을 하나 이상 선택해 주세요');
        return false;
    }
});

//검수용 등록 버튼
$('[name="btn_review_on"]').on('click',function(e){
    e.preventDefault();
    var chk_len = $('[name="check_item[]"]:checked').length;
    if (chk_len > 0) {
        $('[name="Inspection"]').val(1);
        $('[name="_method"]').val('put');
        $('#form_'+title).attr('action',"/admin/boards/bulk/app_review").submit();
    } else {
        alert('게시물을 하나 이상 선택해 주세요');
        return false;
    }
});

//검수용등록해제 버튼
$('[name="btn_review_off"]').on('click',function(e){
    e.preventDefault();
    var chk_len = $('[name="check_item[]"]:checked').length;
    if (chk_len > 0) {
        $('[name="Inspection"]').val(0);
        $('[name="_method"]').val('put');
        $('#form_'+title).attr('action',"/admin/boards/bulk/app_review").submit();
    } else {
        alert('게시물을 하나 이상 선택해 주세요');
        return false;
    }
});

//face_check 버튼
$("[name='btn_face_check']").on('click',function (e) {
    e.preventDefault();
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    var chk_len = $('[name="check_item[]"]:checked').length;
    if (chk_len > 0) {
        $('[name="_method"]').val('put');
        $('#form_'+title).attr('action', "/admin/boards/bulk/face").submit();
    } else {
        $('#text_check_loading').removeClass('visible');
        $('#text_check_loading').addClass('invisible');
        alert('게시물을 하나 이상 선택해 주세요');
        return false;
    }
});

//3일전 버튼
$("[name='threedaysago']").on('click',function(e){
    var dt = new Date();
    dt.setDate(dt.getDate()-3);
    $('[name="start_date"]').val(dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+(dt.getDate()));
    $('[name="end_date"]').val(dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+(dt.getDate()));
    $('#search_form').attr('action', "/admin/boards/bulk/v2").submit();
});
//2일전 버튼
$("[name='twodaysago']").on('click',function(e){
    var dt = new Date();
    dt.setDate(dt.getDate()-2);
    $('[name="start_date"]').val(dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+(dt.getDate()));
    $('[name="end_date"]').val(dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+(dt.getDate()));
    $('#search_form').attr('action', "/admin/boards/bulk/v2").submit();
});
//1일전 버튼
$("[name='onedaysago']").on('click',function(e){
    var dt = new Date();
    dt.setDate(dt.getDate()-1);
    $('[name="start_date"]').val(dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+(dt.getDate()));
    $('[name="end_date"]').val(dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+(dt.getDate()));
    $('#search_form').attr('action', "/admin/boards/bulk/v2").submit();
});

//today 버튼
$("[name='today']").on('click',function(e){
    var dt = new Date();
    $('[name="start_date"]').val(dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+(dt.getDate()));
    $('[name="end_date"]').val(dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+(dt.getDate()));
    $('#search_form').attr('action', "/admin/boards/bulk/v2").submit();
});
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
    }

    yPosition = 48;

    $layer.animate({"top":yPosition }, {duration:speed, easing:easing, queue:false});
});

// 2019.2.12 cch 태그자동완성시 엔터키 submit 방지용
$('input[type="text"]').keydown(function() {
    if (event.keyCode === 13) {
        event.preventDefault();
    }
});
$('.dropbtn').on('click',function(e){
    var target_id = this.id.split('_')[0]+"_dropdown-content";
    e.preventDefault
    if($('[id='+target_id+']').hasClass('view'))
        $('[id='+target_id+']').removeClass('view');
    else
        $('[id='+target_id+']').addClass('view');
});

$('.dropdown').mouseover(function() {
    var target_id = this.id.split('_')[0]+"_dropdown-content";
    $('[id='+target_id+']').css('display','block');
});

$('.dropdown').mouseout(function() {
    var target_id = this.id.split('_')[0]+"_dropdown-content";
    $('[id='+target_id+']').css('display','none');
});

$('[name="alldrop"]').on('click',function(){
    if($('.dropdown-content').hasClass('view'))
        $('.dropdown-content').removeClass('view');
    else
        $('.dropdown-content').addClass('view');
});

//개별 메뉴에서 수정 클릭시
$(document).on("click", "[name='individual_modify_tag']" , function(e) {
    e.preventDefault();
    var modify_chk_array = Array();
    modify_chk_array[0]=e.target.value;
    $('#individual_modify_tag_id').val(modify_chk_array[0]);
    $.ajax({
        url:"/admin/boards/bulk/tag/common?type="+title,
        method:"get",
        data :{
            'check_item':modify_chk_array
        },
        dataType:"json",
    })
        .done(function(json){
            individual_tag_list_modify.tags=[];
            for(var i  in json.result){
                if(json.result[i] != null && json.result[i] != ''){
                    individual_tag_list_modify.tags.push(json.result[i]);
                }
            }
            individual_tag_list_modify.ori_tags=[];
            for(var i in json.ori_tags){
                if(json.ori_tags[i] != null && json.ori_tags[i] != ''){
                    individual_tag_list_modify.ori_tags.push(json.ori_tags[i]);
                }
            }
            $('#common_tags').val(json.common_tags);
            individual_modify_dialog.dialog( "open" );
        });
});

//2019.01.28 cch 태그수정
$("#btn_individual_modify_tags").on('click',function(e){
    e.preventDefault();
    var str =individual_tag_list_modify.tags.join();
    console.log(str);
    $('#send_tag').val(str);
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    var individual_modify_chk_array=Array();
    individual_modify_chk_array[0]= $('#individual_modify_tag_id').val();
    console.log(individual_modify_chk_array);
    $.ajax({
        url: "/admin/boards/bulk/tag",
        type: "post",
        data: {
            "_method":'put',
            "_token": token,
            'individual': true,
            'send_tag': $('#send_tag').val(),
            'check_item': individual_modify_chk_array,
            'common_tags': $('#common_tags').val()
        }
    })
        .done(function (json) {
            if(json.result =='done'){
                modify_dialog.dialog( "close" );
                // console.log();
                location.reload();
            }

            // $('#search_form').attr('action', "/admin/boards").submit();
        });

});
//@end 2019.01.28 cch 태그수정

//개별 게시물 남자로 변경 버튼
$(document).on("click", "[name='individual_move_to_man']" , function(e) {
    e.preventDefault();
    var chk_array = Array();
    chk_array[0] = e.target.value;
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    if ($('[id='+e.target.value+'_gender] > [name="individual_move_to_man"]').hasClass('btn-outline-primary')) {
        $.ajax({
            url: "/admin/boards/bulk/gender",
            type: "post",
            data: {
                'change_gender':1,
                "_method":'put',
                'type'  :   title,
                "_token": token,
                'individual':true,
                'check_item': chk_array
            }
        })
            .done(function () {
                $('[id='+e.target.value+'_gender] > [name="individual_move_to_man"]').addClass('btn-primary');
                $('[id='+e.target.value+'_gender] > [name="individual_move_to_man"]').removeClass('btn-outline-primary');
                $('[id='+e.target.value+'_gender] > [name="individual_move_to_woman"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_gender] > [name="individual_move_to_woman"]').addClass('btn-outline-primary');
                $('#text_check_loading').removeClass('visible');
                $('#text_check_loading').addClass('invisible');
                var target_id = e.target.value+"_dropdown-content";
                $('[id='+target_id+']').css('background','rgba(234,204,026,0.7)');
            });
    }else{
        $('#text_check_loading').removeClass('visible');
        $('#text_check_loading').addClass('invisible');
    }

});

////개별 게시물 여자로 변경 버튼
$(document).on("click", "[name='individual_move_to_woman']" , function(e) {
    e.preventDefault();
    var chk_array = Array();
    chk_array[0] = e.target.value;
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    if ($('[id='+e.target.value+'_gender] > [name="individual_move_to_woman"]').hasClass('btn-outline-primary')) {
        $.ajax({
            url: "/admin/boards/bulk/gender",
            type: "post",
            data: {
                'change_gender':2,
                "_method":'put',
                'type'  :   title,
                "_token": token,
                'individual':true,
                'check_item': chk_array
            }
        })
            .done(function (json) {
                $('[id='+e.target.value+'_gender] > [name="individual_move_to_woman"]').addClass('btn-primary');
                $('[id='+e.target.value+'_gender] > [name="individual_move_to_woman"]').removeClass('btn-outline-primary');
                $('[id='+e.target.value+'_gender] > [name="individual_move_to_man"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_gender] > [name="individual_move_to_man"]').addClass('btn-outline-primary');
                $('#text_check_loading').removeClass('visible');
                $('#text_check_loading').addClass('invisible');
                var target_id = e.target.value+"_dropdown-content";
                $('[id='+target_id+']').css('background','rgba(234,204,026,0.7)');
            });
    }else{
        $('#text_check_loading').removeClass('visible');
        $('#text_check_loading').addClass('invisible');
    }
});
//개별 오픈
$(document).on("click", "[name='individual_open']" , function(e) {
    e.preventDefault();
    var chk_array = Array();
    chk_array[0] = e.target.value;
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    if ($('[id='+e.target.value+'_open] > [name="individual_open"]').hasClass('btn-outline-primary')) {
        $.ajax({
            url:"/admin/boards/bulk/open",
            type: "post",
            data :{
                'change_state':1,
                'type'  :   title,
                "_method":'put',
                "_token": token,
                'individual':true,
                'check_item':chk_array
            }
        })
            .done(function(){
                $('[id='+e.target.value+'_open] > [name="individual_open"]').addClass('btn-primary');
                $('[id='+e.target.value+'_open] > [name="individual_open"]').removeClass('btn-outline-primary');
                $('[id='+e.target.value+'_open] > [name="individual_close"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_open] > [name="individual_close"]').addClass('btn-outline-primary');
                $('[id='+e.target.value+'_open] > [name="not_checked_to_open"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_open] > [name="not_checked_to_open"]').addClass('btn-outline-primary');
                $('#text_check_loading').removeClass('visible');
                $('#text_check_loading').addClass('invisible');
                var target_id = e.target.value+"_dropdown-content";
                $('[id='+target_id+']').css('background','rgba(234,204,026,0.7)');
            });
    }else{
        $('#text_check_loading').removeClass('visible');
        $('#text_check_loading').addClass('invisible');
    }
});
//개별 내림
$(document).on("click", "[name='individual_close']" , function(e) {
    e.preventDefault();
    var chk_array = Array();
    chk_array[0] = e.target.value;
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    if ($('[id='+e.target.value+'_open] > [name="individual_close"]').hasClass('btn-outline-primary')) {
        $.ajax({
            url:"/admin/boards/bulk/open",
            type: "post",
            data :{
                'change_state':2,
                'type'  :   title,
                "_method":'put',
                "_token": token,
                'individual':true,
                'check_item':chk_array
            }
        })
            .done(function(){
                $('[id='+e.target.value+'_open] > [name="individual_close"]').addClass('btn-primary');
                $('[id='+e.target.value+'_open] > [name="individual_close"]').removeClass('btn-outline-primary');
                $('[id='+e.target.value+'_open] > [name="individual_open"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_open] > [name="individual_open"]').addClass('btn-outline-primary');
                $('[id='+e.target.value+'_open] > [name="not_checked_to_open"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_open] > [name="not_checked_to_open"]').addClass('btn-outline-primary');
                $('#text_check_loading').removeClass('visible');
                $('#text_check_loading').addClass('invisible');
                var target_id = e.target.value+"_dropdown-content";
                $('[id='+target_id+']').css('background','rgba(234,204,026,0.7)');
            });
    }else{
        $('#text_check_loading').removeClass('visible');
        $('#text_check_loading').addClass('invisible');
    }
});


$(document).on('click','[name="not_checked_to_open"]',function(e){

});
//todo
$(document).on("click", "[name='individual_not']" , function(e) {
    e.preventDefault();
    var chk_array = Array();
    chk_array[0] = e.target.value;
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    if ($('[id='+e.target.value+'_check] > [name="individual_not"]').hasClass('btn-outline-primary')) {
        $.ajax({
            url:"/admin/boards/bulk/text",
            type: "post",
            data :{
                'type'  :   title,
                'change_text_check':1,
                "_method":'put',
                "_token": token,
                'individual':true,
                'check_item':chk_array
            }
        })
            .done(function(){
                $('[id='+e.target.value+'_check] > [name="individual_not"]').addClass('btn-primary');
                $('[id='+e.target.value+'_check] > [name="individual_not"]').removeClass('btn-outline-primary');
                $('[id='+e.target.value+'_check] > [name="individual_text"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_check] > [name="individual_text"]').addClass('btn-outline-primary');
                $('[id='+e.target.value+'_check] > [name="not_checked"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_check] > [name="not_checked"]').addClass('btn-outline-primary');
                $('#text_check_loading').removeClass('visible');
                $('#text_check_loading').addClass('invisible');
                var target_id = e.target.value+"_dropdown-content";
                $('[id='+target_id+']').css('background','rgba(234,204,026,0.7)');
            });
    }else{
        $('#text_check_loading').removeClass('visible');
        $('#text_check_loading').addClass('invisible');
    }
});

$(document).on("click", "[name='individual_text']" , function(e) {
    e.preventDefault();
    var chk_array = Array();
    chk_array[0] = e.target.value;
    $('#text_check_loading').removeClass('invisible');
    $('#text_check_loading').addClass('visible');
    if ($('[id='+e.target.value+'_check] > [name="individual_text"]').hasClass('btn-outline-primary')) {
        $.ajax({
            url:"/admin/boards/bulk/text",
            type: "post",
            data :{
                'type'  :   title,
                'change_text_check':2,
                "_method":'put',
                "_token": token,
                'individual':true,
                'check_item':chk_array
            }
        })
            .done(function(){
                $('[id='+e.target.value+'_check] > [name="individual_text"]').addClass('btn-primary');
                $('[id='+e.target.value+'_check] > [name="individual_text"]').removeClass('btn-outline-primary');
                $('[id='+e.target.value+'_check] > [name="individual_not"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_check] > [name="individual_not"]').addClass('btn-outline-primary');
                $('[id='+e.target.value+'_check] > [name="not_checked"]').removeClass('btn-primary');
                $('[id='+e.target.value+'_check] > [name="not_checked"]').addClass('btn-outline-primary');
                $('#text_check_loading').removeClass('visible');
                $('#text_check_loading').addClass('invisible');
                var target_id = e.target.value+"_dropdown-content";
                $('[id='+target_id+']').css('background','rgba(234,204,026,0.7)');
            });
    }else{
        $('#text_check_loading').removeClass('visible');
        $('#text_check_loading').addClass('invisible');
    }
});
//게시 vue 인스턴스

var tag_list_open = new Vue({
    el: '#open',
    data: {
        newTag: '',
        tags: []
    },
    name: "open",
    components: {
        open_autocomplete: Autocomplete_open
    },
    methods: {
        removeTag(event){
            var tag = event.target.textContent;
            var index = this.tags.indexOf(tag);
            this.tags.splice(index,1);
        }
    }
});

var open_dialog = $( "#dialog-form" ).dialog({
    autoOpen: false,
    width: 700,
    modal: true,
});
//수정 vue 인스턴스
var tag_list_modify = new Vue({
    el: '#modify',
    data: {
        newTag: '',
        tags: [],
        ori_tags:[]
    },
    name: "modify",
    components: {
        modify_autocomplete: Autocomplete_modify
    },

    methods: {
        removeTag(event){
            var tag = event.target.textContent;
            var index = this.tags.indexOf(tag);
            this.tags.splice(index,1);
        }
    }
});

var modify_dialog = $('#modify_tag_form').dialog({
    autoOpen: false,
    width: 700,
    modal: true,
});
//수정 vue 인스턴스
var individual_tag_list_modify = new Vue({
    el: '#individual_modify',
    data: {
        newTag: '',
        tags: [],
        ori_tags:[]
    },
    name: "modify",
    components: {
        modify_autocomplete: Autocomplete_modify
    },
    methods: {
        removeTag(event){
            var tag = event.target.textContent;
            var index = this.tags.indexOf(tag);
            this.tags.splice(index,1);
        }
    }
});

var individual_modify_dialog = $('#individual_modify_tag_form').dialog({
    autoOpen: false,
    width: 700,
    modal: true,
});
var tag_list_input = new Vue({
    el:"#input",
    name:'input',
    data:{
        newTag:'',
        tags:[],
    },
    components:{
        input_autocomplete:Autocomplete_input
    },
    methods:{
        removeTag(event){
            var tag = event.target.textContent;
            var index = this.tags.indexOf(tag);
            this.tags.splice(index,1);
        }
    }
});
var create_dialog = $('#create_form').dialog({
    autoOpen:false,
    width:1000,
    modal:true,
});

// 2019.2.12 cch 등록폼 alert
$("#btn_create").on('click',function(e){
    e.preventDefault();
    create_dialog.dialog('open');
});

$("#create_article").on('click',function(e){
    e.preventDefault();
    var str =tag_list_input.tags.join();
    $('#create_tag').val(str);
    $('#create_'+title).attr('action', '/admin/boards?type='.title).submit();
});

$(document).ready(function() {
    $(document).on('change', 'input[type=file]', function (e) {
        var $target = $(this);
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
                $('#blah').css('width','320');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
});
