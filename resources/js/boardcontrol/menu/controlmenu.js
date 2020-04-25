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