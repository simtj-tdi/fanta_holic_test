/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
// //
// require('./bootstrap');
// //
// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// 연습예제
//  const files = require.context('./', true, /\.vue$/i)
//  files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default)).
//  Vue.component('test',require('./components/test.vue'));

// {{--InputTool 자동완성--}}
Autocomplete_input = Vue.component('Autocomplete_input', require('./components/autocomplete/input.vue').default);

// {{--SearchTool 자동완성--}}
Autocomplete_search = Vue.component('Autocomplete_search',require('./components/autocomplete/search.vue').default);

//키워드검색 자동완성
Autocomplete_s_search = Vue.component('Autocomplete_s_search',require('./components/autocomplete/s_search.vue').default);

// {{--opehTool 자동완성--}}
Autocomplete_open = Vue.component('Autocomplete_open',require('./components/autocomplete/open.vue').default);

// {{--수정폼 vue--}
Autocomplete_modify = Vue.component('Autocomplete_modify',require('./components/autocomplete/modify.vue').default);

Crawling_Autocomplete_input = Vue.component('Crawling_Autocomplete_input',require('./components/autocomplete/crawling_input.vue').default);

//search tool 에러나서 보류 vue 공부 좀 더 필요
// Search_tool_form = Vue.component('Search_tool_form', require('./components/form/search_tool.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */




