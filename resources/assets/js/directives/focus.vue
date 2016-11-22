<script>
    export default {
        inserted: function(el){
            Vue.nextTick( () => el.focus() );
        }
    };
</script>
