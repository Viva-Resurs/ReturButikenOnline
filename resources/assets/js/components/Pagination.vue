<template>
    <div class="pagination">
        <div class="two fields">

        <div v-if="total!=0 && total>limit" class="pagination_left">

            <div v-if="showPagination" class="ui floated left pagination menu">

                    <a class="icon item" @click="firstPage">
                        <i class="angle double left icon"></i>
                    </a>

                    <a class="icon item" @click="prevPage">
                        <i class="angle left icon"></i>
                    </a>

                    <a v-for="n in totalPages" :class="'item '+((n==currentPage)?'active':'')" @click="toPage(n)">{{n}}
                    </a>

                    <a class="icon item" @click="nextPage">
                        <i class="angle right icon"></i>
                    </a>

                    <a class="icon item" @click="lastPage">
                        <i class="angle double right icon"></i>
                    </a>

            </div>
            <template v-else>

                <slot name="replacePagination">

                </slot>

            </template>
        </div>

        <div v-else class="pagination_left">
        </div>

        <div v-show="total>limitOptions[0] && showPagination" class="pagination_right ui floated right secondary menu">
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

<script lang="coffee">
    module.exports = {

        name: 'Pagination'

        props: [ 'total', 'showPagination' ]

        data: ->
            offset: 0
            limit: 10
            limitOptions: [ 10, 50, 100, 500 ]

        computed:

            currentPage: ->
                p = 1
                c = 0
                i = 0
                while i < @total
                    if (i==@offset)
                        return p;
                    if (c+1==@limit && i<@total-p)
                        p++; c=0;
                    i++
                    c++

            totalPages: ->
                p = 1
                c = 0
                i = 0
                while i < @total
                    if (c+1==@limit && i<@total-p)
                        p+=1; c=0;
                    i++
                    c++

                return p;


        methods:

            firstPage: ->
                @offset = 0;

            prevPage: ->
                if (@offset-@limit<0)
                    return @firstPage();
                @offset -= @limit;

            toPage: (p) ->
                @offset = (p-1)*@limit;

            nextPage: ->
                if (@offset>=@total-@limit)
                    return @lastPage();
                @offset += @limit;

            lastPage: ->
                @offset = @total-(((@total-1)%@limit)+1);


        watch:
            limit: (val, oldVal) ->
                bus.$emit('limit_changed',val);

            offset: (val, oldVal) ->
                bus.$emit('offset_changed',val);


        created: ->

            window.onkeyup = (e) =>
                key = e.which || e.keyCode
                if (key == 37)
                    @prevPage()
                if (key == 39)
                    @nextPage()

        beforeDestroy: ->
            window.onkey = false;

    }
</script>
