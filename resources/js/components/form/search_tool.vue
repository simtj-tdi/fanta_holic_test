<template>
    <form class="form-inline" name="search_form" id="search_form" method="GET" action="/board/instagram">
    <div style="background:#EFEFEF; border:1px solid #cfcfcf">
        <div class="box form-group mb-2" style="width: 689px;height: 79px;">
            <div style="display: inline-block">
                <p>연동날짜</p>
                <input type="text" class="form-control datetimepicker" id="start_date" name="start_date" autocomplete="off" style="width:100px;height: 30px;display: inline-block;font-size: 14px;">
                    &nbsp;&nbsp;~&nbsp;&nbsp;
                <input type="text" class="form-control datetimepicker" id="end_date" name="end_date" autocomplete="off" style="width:100px;height: 30px;display: inline-block;font-size: 14px;">
            </div>
            <div style="display: inline-block">
                <p>태그검색</p>
                <div id="search">
                    <search_autocomplete></search_autocomplete>
                </div>
                <button type="submit" class="btn ml-3" >검색</button>
            </div>
        </div>
        <div class="box form-group mb-2" style="width:398px">
            <p>텍스트 검수상태</p>
            <div class="custom-radio" id='textcheck-group'>
                <button type="radio" class="btn" name="text_check" value="3" >전체</button>
                <button type="radio" class="btn" name="text_check" value="0" >미검수</button>
                <button type="radio" class="btn" name="text_check" value="1" >text없음</button>
                <button type="radio" class="btn" name="text_check" value="2" >text있음</button>
            </div>
        </div>
        <div class="box form-group mb-2">
            <p>게시물 검수상태</p>
            <div  class="custom-radio" id='state-group'>
                <button type="radio" class="btn" name="state" value="">전체</button>
                <button type="radio" class="btn" name="state" value="1" >게시</button>
                <button type="radio" class="btn" name="state" value="0" >내림</button>
            </div>
        </div>
        <div class="box form-group mb-2">
            <p>성별</p>
            <div class="custom-radio" id='gender-group' >
                <button type="radio" class="btn" name="gender" value="1">남자</button>
                <button type="radio" class="btn" name="gender" value="2">여자</button>
            </div>
        </div>
    </div>
    </form>
</template>

<script>
    import Autocomplete_search from '../autocomplete/search.vue'
    export default {
        name: "search_tool_form",
        props: {
            items: {
                type: Array,
                required: false,
                default:[]
            },
        }
    }
    new Vue({
        el:'#search',
        extends: {
            search_autocomplete: Autocomplete_search
        },
    })

</script>

<style scoped>

    div {
        border-radius: 6px;
    }
</style>