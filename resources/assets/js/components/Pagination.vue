<template>
    <div class="pagination">
        <div class="two fields">

        <div v-if="total>limit" class="pagination_left ui floated left pagination menu">

            <template v-if="showPagination">

                    <a class="icon item" @click="firstPage">
                        <i class="angle dubble left icon"></i>
                    </a>

                    <a class="icon item" @click="prevPage">
                        <i class="angle left icon"></i>
                    </a>

                    <a v-for="n in totalPages" :class="'icon item '+((n==currentPage)?'primary':'default')" @click="toPage(n)">{{n}}
                    </a>

                    <a class="icon item" @click="nextPage">
                        <i class="angle right icon"></i>
                    </a>

                    <a class="icon item" @click="lastPage">
                        <span class="angle double right icon"></span>
                    </a>


            </template>
            <template v-else>

                <slot name="replacePagination">

                </slot>

            </template>
        </div>

        <div v-else class="pagination_left">
        </div>

        <div v-show="total>limit && showPagination" class="pagination_right ui floated right secondary menu">
            <div class="pagination_right" style="white-space: nowrap;">
                <select class="ui fluid dropdown v-dropdown dropdown-toggle selectpicker show-tick"
                    v-model="limit"
                    id="limit"
                    ref="select-input"
                >
                    <option v-for="option in limitOptions" :value="option">{{option}}</option>
                </select>
            </div>
        </div>
    </div>
    </div>

</template>

<script>
    export default {

        name: 'Pagination',

        props: [ 'total', 'showPagination' ],

        data: function(){
            return {
                offset: 0,
                limit: 10,
                limitOptions: [ 10, 50, 100, 500 ]
            };
        },

        computed: {

            currentPage(){
                var p = 1;
                for (var i=0, c=0 ; i<this.total ; i++, c++){
                    if (i==this.offset)
                        return p;
                    if (c+1==this.limit && i<this.total-p){
                        p++; c=0;
                    }
                }
            },

            totalPages(){
                var p = 1;
                for (var i=0, c=0 ; i<this.total ; i++, c++){
                    if (c+1==this.limit && i<this.total-p){
                        p++; c=0;
                    }
                }
                return p;
            }

        },

        methods: {

            firstPage(){
                this.offset = 0;
            },

            prevPage(){
                if (this.offset-this.limit<0)
                    return this.firstPage();
                this.offset -= this.limit;
            },

            toPage(p){
                this.offset = (p-1)*this.limit;
            },

            nextPage(){
                if (this.offset>this.total-this.limit)
                    return this.lastPage();
                this.offset += this.limit;
            },

            lastPage(){
                this.offset = this.total-(((this.total-1)%this.limit)+1);
            }

        },

        watch: {
            limit: function(val, oldVal){
                bus.$emit('limit_changed',val);
            },
            offset: function(val, oldVal){
                bus.$emit('offset_changed',val);
            }
        },

        created: function(){

            /*
            this.$nextTick(function() {

                var target = $(this.$els.selectInput);

                let g = target.selectpicker({
                    size: 4,
                    iconBase: 'fa',
                    tickIcon: 'fa-check'
                });

                target.selectpicker('refresh');

            });
            */

            window.onkeyup = (function(e) {
                var key = e.which || e.keyCode;
                if (key == 37)
                    this.prevPage();
                if (key == 39)
                    this.nextPage();
            }).bind(this);

        },

        beforeDestroy: function () {
            window.onkey = false;
        }

    };
</script>
