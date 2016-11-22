<template>
    <div class="ui grid">
        <div v-if="total!=0 && total>limit && showPagination" class="twelve wide column">

            <div class="ui grid computer only pagination menu">
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

            <div class="ui grid mobile tablet only pagination menu">
                <a class="icon item" @click="firstPage">
                    <i class="angle double left icon"></i>
                </a>

                <a class="icon item" @click="prevPage">
                    <i class="angle left icon"></i>
                </a>

                <a class="icon item">
                    <i>Page {{currentPage}} ... {{totalPages}}</i>
                </a>

                <a class="icon item" @click="nextPage">
                    <i class="angle right icon"></i>
                </a>

                <a class="icon item" @click="lastPage">
                    <i class="angle double right icon"></i>
                </a>
            </div>

        </div>

        <div v-else class="twelve wide column">
            <div class="ui grid">
                <slot name="replacePagination">
                </slot>
            </div>

        </div>

        <div class="four wide column right aligned">
            <select class="ui selection dropdown" v-show="total>limitOptions[0]"
                v-dropdown v-model="limit" id="limit"
                >
                <option class="item" v-for="option in limitOptions" :value="option">{{option}}</option>
            </select>
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
