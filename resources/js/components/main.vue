<script>
    import Vue from 'vue'
    export default {
        methods: {
            show: function () {
                $(this.$el).modal('show');
                this.productList = [];
                this.total = 0;
            }
        }
</script>
