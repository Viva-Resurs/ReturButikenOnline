<template lang="pug">
    div.ui.grid.equal.width
        div.column.mobile.only
            div.fitted.item
                div.ui.buttons
                    div.ui.button.large.item.icon.top.right.pointing.dropdown#pagination( v-dropdown="" )                        
                        i.sticky.note.outline.icon.icon-style
                        | &nbsp {{currentPage}}/{{totalPages}}                      
                        div.menu                           
                            div.ui.item.fluid
                                div.ui.fluid.dropdown#limit( v-dropdown="")                                    
                                    div.right.aligned.column
                                        i.dropdown.right.icon                                    
                                    div.default.value.right.aligned.column {{ limit }}
                                    div.right.menu
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
                button.ui.large.icon.right.item.button.disabled#tool_paginate_right_item
                    i {{ translate('page') }} {{currentPage}} / {{totalPages}}
                button.ui.large.icon.right.item.button( @click="nextPage" )
                    i.angle.right.icon.icon-style
                button.ui.large.icon.right.item.button( @click="lastPage" )
                    i.angle.double.right.icon.icon-style
                div.ui.button.large.right.item.icon.top.left.pointing.dropdown#limit( v-dropdown="" )
                    div.default.value#tool_paginate_limit
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
            ###*
            #   Returns the current page.
            #   @return {currentPage} current page
            ###
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

            ###*
            #   Returns total number of pages.
            #   @return {totalPages} total number of pages
            ###
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
            ###*
            #   Changes limit to a new value.
            #   @param {value} new limit value
            ###
            change_limit: (value) ->
                Vue.set this, 'limit', value
                bus.$emit 'limit_changed', value

            ###*
            #   Moves to first page.
            ###
            firstPage: ->
                bus.$emit 'offset_changed', 0

            ###*
            #   Moves to previous page.
            ###
            prevPage: ->
                if @offset-@limit < 0
                    return @firstPage()
                bus.$emit 'offset_changed', @offset - @limit

            ###*
            #   Moves to a selected page.
            #   @param {p} page to select
            ###
            toPage: (p) ->
                bus.$emit 'offset_changed', (p-1) * @limit

            ###*
            #   Moves to next page.
            ###
            nextPage: ->
                if @offset >= @total-@limit
                    return @lastPage()
                bus.$emit 'offset_changed', @offset + @limit

            ###*
            #   Moves to last page.
            ###
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

<style>
    #tool_paginate_right_item, #tool_paginate_limit {
        line-height: 12px;
    }
</style>