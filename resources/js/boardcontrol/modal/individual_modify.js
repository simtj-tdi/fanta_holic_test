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