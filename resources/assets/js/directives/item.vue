<script>
    export default {
        inserted: function(el){
            $(el).on('updated',function(e,arg){
                if (!arg)
                    return;
                $('#'+arg).transition({
                    animation : 'pulse',
                    onStart : function(){
                        this.classList.add('positive')
                    },
                    onComplete : function(){
                        this.classList.remove('positive')
                    }
                });
            })
            $(el).on('removed',function(e,arg,cb){
                console.log('removing '+arg)
                if (!arg)
                    return;
                $('#'+arg).transition({
                    animation : 'fade',
                    onStart : function(){
                        this.classList.add('negative')
                    },
                    onComplete : cb
                });
            })
        }
    };
</script>
