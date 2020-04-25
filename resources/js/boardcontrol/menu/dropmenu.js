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