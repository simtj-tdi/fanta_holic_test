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