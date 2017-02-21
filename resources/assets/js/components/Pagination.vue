<template lang="pug">
    div.ui.grid.fluid
        div.two.column.row.stackable
            div.left.floated.column( v-if="total!=0 && total>limit && showPagination" )
                div.ui.grid.computer.only.pagination.menu
                    a.icon.item( @click="firstPage" )
                        i.angle.double.left.icon

                    a.icon.item( @click="prevPage" )
                        i.angle.left.icon

                    a( v-for="n in totalPages" ":class"="'item '+((n==currentPage)?'active':'')" @click="toPage(n)" ) {{n}}

                    a.icon.item( @click="nextPage" )
                        i.angle.right.icon

                    a.icon.item( @click="lastPage" )
                        i.angle.double.right.icon

                div.ui.grid.mobile.tablet.only.pagination.menu
                    a.icon.item( @click="firstPage" )
                        i.angle.double.left.icon

                    a.icon.item( @click="prevPage" )
                        i.angle.left.icon

                    a.icon.item
                        i Page {{currentPage}} ... {{totalPages}}

                    a.icon.item( @click="nextPage" )
                        i.angle.right.icon

                    a.icon.item( @click="lastPage" )
                        i.angle.double.right.icon

            div.left.floated.column( v-else="" )
                div.ui.grid
                    slot( name="replacePagination" )

            div.right.floated.right.aligned.column
                    select.selection.dropdown#limit(
                        v-show="total>limitOptions[0]"
                        v-dropdown="" v-model="limit" )
                        option.item( v-for="option in limitOptions" ":value"="option") {{option}}
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
