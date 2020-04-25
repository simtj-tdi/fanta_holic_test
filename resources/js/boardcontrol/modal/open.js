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