//search vue 인스턴스 태그검색 자동완성
var search_list_search = new Vue({
    el:'#s_search',
    name:'s_search',
    data:{
        newTag:'',
    },
    components:{
        s_search_autocomplete: Autocomplete_s_search
    }
});