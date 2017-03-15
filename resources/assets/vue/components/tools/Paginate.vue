<template lang="pug">
    div.ui.grid.equal.width
        div.column.mobile.only
            div.ui.button.icon.item.dropdown#pagination( v-dropdown="" )
                i.sticky.note.icon
                div.menu
                    div.header {{ translate('tool.pagination') }}

                    div.text
                        div.ui.grid.two.columns
                            div.left.aligned.column {{ translate('page') }} {{currentPage}} / {{totalPages}}
                            div.right.aligned.column
                                div.ui.right.basic.compact.button.dropdown#limit( v-dropdown="" )
                                    div.default.value {{ limit }}
                                        i.dropdown.left.icon
                                    div.left.menu
                                        div.item(
                                            v-for="option in limitOptions"
                                            ":class"="(limit==option)?'active':''"
                                            @click="change_limit(option)"
                                            )
                                            | {{ option }}

                    div.ui.basic.compact.buttons.bottom.attached.fluid
                        button.ui.button( @click="firstPage" )
                            i.angle.double.left.icon
                        button.ui.button( @click="prevPage" )
                            i.angle.left.icon
                        button.ui.button( @click="nextPage" )
                            i.angle.right.icon
                        button.ui.button( @click="lastPage" )
                            i.angle.double.right.icon

        div.column.tablet.only
            div.left.menu
                a.item( @click="firstPage" )
                    i.angle.double.left.icon
                a.item( @click="prevPage" )
                    i.angle.left.icon
                div.item
                    i {{ translate('page') }} {{currentPage}} / {{totalPages}}
                a.item( @click="nextPage" )
                    i.angle.right.icon
                a.item( @click="lastPage" )
                    i.angle.double.right.icon

                div.ui.button.item.dropdown#limit( v-dropdown="" )
                    div.default.value {{ limit }}
                        i.dropdown.icon
                    div.menu
                        div.item(
                            v-for="option in limitOptions"
                            ":class"="(limit==option)?'active':''"
                            @click="change_limit(option)"
                            )
                            | {{ option }}

        div.column.computer.only( v-if="total!=0 && showPagination" )
            div.ui.compact.menu
                a.icon.item( @click="firstPage" )
                    i.angle.double.left.icon

                a.icon.item( @click="prevPage" )
                    i.angle.left.icon

                a( v-for="n in totalPages" ":class"="'item '+((n==currentPage)?'active':'')" @click="toPage(n)" ) {{n}}

                a.icon.item( @click="nextPage" )
                    i.angle.right.icon

                a.icon.item( @click="lastPage" )
                    i.angle.double.right.icon

                a.ui.item.compact.button.dropdown#limit( v-dropdown="" )
                    div.default.value {{ limit }}
                        i.dropdown.icon
                    div.menu
                        div.item(
                            v-for="option in limitOptions"
                            ":class"="(limit==option)?'active':''"
                            @click="change_limit(option)"
                            )
                            | {{ option }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Paginate'
        props: ['offset', 'total', 'showPagination']
        data: ->
            limit: 10
            limitOptions: [ 10, 50, 100, 500 ]

        computed:
            currentPage: ->
                p = 1
                c = 0
                i = 0
                while i < @total
                    if i == @offset
                        return p
                    if c+1 == @limit && i < @total-p
                        p++
                        c = 0
                    i++
                    c++
                return p

            totalPages: ->
                p = 1
                c = 0
                i = 0
                while i < @total
                    if (c+1==@limit && i<@total-p)
                        p++
                        c = 0
                    i++
                    c++
                return p

        methods:
            change_limit: (value) ->
                Vue.set this, 'limit', value
                bus.$emit 'limit_changed', value

            firstPage: ->
                bus.$emit 'offset_changed', 0

            prevPage: ->
                if @offset-@limit < 0
                    return @firstPage()
                bus.$emit 'offset_changed', @offset - @limit

            toPage: (p) ->
                bus.$emit 'offset_changed', (p-1) * @limit

            nextPage: ->
                if @offset >= @total-@limit
                    return @lastPage()
                bus.$emit 'offset_changed', @offset + @limit

            lastPage: ->
                bus.$emit 'offset_changed', @total-(((@total-1)%@limit)+1)

        created: ->
            window.onkeyup = (e) =>
                key = e.which || e.keyCode
                if key == 37
                    @prevPage()
                if key == 39
                    @nextPage()
        beforeDestroy: ->
            window.onkey = false
</script>
