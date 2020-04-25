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
