<template lang="pug">
    div.ui.grid.equal.width
        div.column.mobile.only
            div.fitted.item
                div.ui.buttons
                    div.ui.button.large.item.icon.top.right.pointing.dropdown#pagination( v-dropdown="" )
                        i.sticky.note.icon.icon-style
                        div.menu
                            div.header {{ translate('tool.pagination') }}

                            div.text.ui.item
                                div.ui.grid.two.columns
                                    div.left.aligned.column
                                        p.middle.aligned {{ translate('page') }} {{currentPage}} / {{totalPages}}
                                    div.right.aligned.column
                                        div.ui.two.columns.right.basic.compact.button.fluid.dropdown#limit( v-dropdown="" )
                                            div.left.aligned.column
                                                i.dropdown.left.icon
                                            div.default.value.right.aligned.column {{ limit }}
                                            div.left.menu
                                                div.item(
                                                    v-for="option in limitOptions"
                                                    ":class"="(limit==option)?'active':''"
                                                    @click="change_limit(option)"
                                                    )
                                                    | {{ option }}

                            div.ui.basic.compact.buttons.bottom.attached.fluid
                                button.ui.button( @click="firstPage" )
                                    i.angle.double.left.icon.icon-style
                                button.ui.button( @click="prevPage" )
                                    i.angle.left.icon.icon-style
                                button.ui.button( @click="nextPage" )
                                    i.angle.right.icon.icon-style
                                button.ui.button( @click="lastPage" )
                                    i.angle.double.right.icon.icon-style

        div.column.tablet.only
            div.ui.buttons
                button.ui.large.icon.right.item.button( @click="firstPage" )
                    i.angle.double.left.icon.icon-style
                button.ui.large.icon.right.item.button( @click="prevPage" )
                    i.angle.left.icon.icon-style
                button.ui.large.icon.right.item.button.disabled( style="line-height: 12px;" )
                    i {{ translate('page') }} {{currentPage}} / {{totalPages}}
                button.ui.large.icon.right.item.button( @click="nextPage" )
                    i.angle.right.icon.icon-style
                button.ui.large.icon.right.item.button( @click="lastPage" )
                    i.angle.double.right.icon.icon-style
                div.ui.button.large.right.item.icon.top.left.pointing.dropdown#limit( v-dropdown="" )
                    div.default.value( style="line-height: 12px;" )
                        | {{ limit }} &nbsp;
                    i.dropdown.icon
                    div.menu
                        div.item(
                            v-for="option in limitOptions"
                            ":class"="(limit==option)?'active':''"
                            @click="change_limit(option)"
                            )
                            | {{ option }}

        div.column.computer.only.right.floated.right.aligned( v-if="total!=0 && showPagination" )
            div.ui.compact.menu.right.aligned
                a.icon.item( @click="firstPage" )
                    i.angle.double.left.icon.icon-style

                a.icon.item( @click="prevPage" )
                    i.angle.left.icon.icon-style

                a( v-for="n in totalPages" ":class"="'item '+((n==currentPage)?'active':'')" @click="toPage(n)" ) {{n}}

                a.icon.item( @click="nextPage" )
                    i.angle.right.icon.icon-style

                a.icon.item( @click="lastPage" )
                    i.angle.double.right.icon.icon-style

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
