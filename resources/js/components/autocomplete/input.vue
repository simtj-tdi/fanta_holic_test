<!--가져다 쓸라면 onEnter tmeplate 수정 필요-->

<template>
    <div>
        <input type="text"  id="ori_tag[]" name="ori_tag"
               placeholder="# (Tag) 입력 칸"
               v-model="newTag"
               @input="onChange" @keyup.down="onArrowDown" @keyup.up="onArrowUp" @keyup.enter="onEnter" autocomplete="off">
        <ul id="results" v-show="isOpen" class="results">
            <li class="loading" v-if="isLoading">
                Loading results...
            </li>
            <li v-else v-for="(result, i) in results" :key="i" @click="setResult(result)" v-text=result class="autocomplete-result" :class="{ 'is-active': i === arrowCounter }">
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            items: {
                type: Array,
                required: false,
                default: []
            },
            isAsync: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        data() {
            return {
                isOpen: false,
                results: [],
                newTag: "",
                isLoading: false,
                arrowCounter: 0
            };
        },
        methods: {
            onChange() {
                // Let's warn the parent that a change was made
                this.$emit("input", this.newTag);
                // Is the data given by an outside ajax request?
                if (this.isAsync) {
                    this.isLoading = true;
                } else {
                    // Let's newTag our flat array
                    this.filterResults();
                    this.isOpen = true;
                }
                this.arrowCounter = 0;
            },
            filterResults() {
                // first uncapitalize all the things
                this.results = this.items.filter(item => {
                    return item.toLowerCase().indexOf(this.newTag.toLowerCase()) > -1;
                });
            },
            setResult(result) {
                this.newTag = result;
                this.isOpen = false;
            },
            onArrowDown() {
                if (this.arrowCounter < this.results.length - 1) {
                    this.arrowCounter = this.arrowCounter + 1;
                    if (this.arrowCounter != 0) {
                        $('.results').scrollTop(this.arrowCounter * 28);
                    }
                }
            },
            onArrowUp() {
                if (this.arrowCounter > 0) {
                    this.arrowCounter = this.arrowCounter - 1;
                    $('.results').scrollTop(this.arrowCounter * 28);;
                }
            },
            onEnter() {
                var blank_pattern = /^\s+|\s+$/g;
                if (this.isOpen == false) {
                    if (this.newTag == '' || this.newTag == null || this.newTag.replace(blank_pattern, '') == "") {
                    } else if (tag_list_input.tags.indexOf(this.newTag) == -1) {
                        tag_list_input.tags.push(this.newTag);
                        this.newTag = '';
                    } else {
                        alert('이미 있는 태그입니다.');
                    }
                    this.arrowCounter = -1;
                } else {
                    if (this.results[this.arrowCounter] == '' || this.results[this.arrowCounter] == null || this.results[this.arrowCounter].replace(blank_pattern, '') == "") {
                    }
                    else {
                        // this.newTag = this.results[this.arrowCounter];
                    }

                    if (this.newTag == '' || this.newTag == null || this.newTag.replace(blank_pattern, '') == "") {
                    } else if (tag_list_input.tags.indexOf(this.newTag) == -1) {
                        tag_list_input.tags.push(this.newTag);
                        this.newTag = '';
                    } else {
                        alert('이미 있는 태그입니다.');
                    }

                    this.isOpen = false;
                    this.arrowCounter = -1;
                }
            },
            handleClickOutside(evt) {
                if (!this.$el.contains(evt.target)) {
                    this.isOpen = false;
                    this.arrowCounter = -1;
                }
            }
        },
        watch: {
            items: function (val, oldValue) {
                // actually compare them
                if (val.length !== oldValue.length) {
                    this.results = val;
                    this.isLoading = false;
                }
            }
        },
        mounted() {
            document.addEventListener("click", this.handleClickOutside);
        },
        destroyed() {
            document.removeEventListener("click", this.handleClickOutside);
        }
    }
</script>

<style scoped>
    .results{
        padding: 0;
        margin: 0;
        color: white;
        background:dimgrey;
        border: 1px solid lightslategrey;
        height: 122px;
        overflow: auto;
        width: 200px;
        position:absolute;
        z-index: 10;
    }
    .autocomplete-result {
        list-style: none;
        text-align: left;
        padding: 4px 2px;
        cursor: pointer;
    }
    .autocomplete-result.is-active,
    .autocomplete-result:hover {
        background-color: #4aae9b;
        color: white;
    }
</style>