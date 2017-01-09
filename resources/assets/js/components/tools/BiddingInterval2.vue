<template lang="pug">
    div
        div.ui.icon.button.hover-default(
            v-tooltip     = ""
            ":data-html"  = "displayInterval(item.bidding_interval)"
            name          = "bidding_interval"
            ":id"         = "item.id"
            ":data-value" = "item.bidding_interval"
            @click        = "showRangePicker"
        )
            i.ui.icon.legal( ":class" = `
                (activeInterval(item.bidding_interval)) ? 'active-interval':''
            ` )

        div.ui.basic.modal(":id"='biddingID')
            div.header Bidding interval
            div.ui.segment
                div.ui.form
                    div.two.stackable.fields
                        div.field
                            h4.ui.sub.header Start date
                            div.ui.small.calendar(":id"="biddingID+'_start'")
                                div.ui.input.left.icon
                                    i.calendar.icon
                                    input( type="text" placeholder="Start" )

                        div.field
                            h4.ui.sub.header End date
                            div.ui.calendar(":id"="biddingID+'_end'")
                                div.ui.input.left.icon
                                    i.calendar.icon
                                    input( type="text" placeholder="End" )

            div.ui.grid.component.padded( style="position: relative; top: -11px;" )
                div.center.aligned.column.actions( style="background: rgba(0,0,0,0.1);")
                    div.ui.small.red.inverted.cancel.button Cancel
                    div.ui.small.green.inverted.approve.button Confirm
</template>

<script lang="coffee">
    module.exports =
        name: 'BiddingInterval2'
        props: [ 'item' ]
        computed:
            biddingID: ->
                return 'bidding_' + @item.id

        methods:
            displayInterval: (interval) ->
                if interval.indexOf '|' < 0
                    return 'Click to set interval'
                dates = interval.split '|'
                if dates.length < 2
                    return false
                return "<b>Start:</b><br>" + dates[0] + "<br><br>"+
                       "<b>End:</b><br>" + dates[1]

            activeInterval: (interval) ->
                if (
                    interval == '' ||
                    typeof interval != 'string' ||
                    interval.split('|')[1] == ''
                )
                    return false
                return moment().isBefore interval.split('|')[1]

            showRangePicker: ->
                id = @biddingID
                el_start = $('#'+id+'_start')
                el_end = $('#'+id+'_end')

                if el_start.data("moduleCalendar") == undefined
                    el_start.calendar({
                        debug: true
                        ampm: false
                        inline: true
                        endCalendar: el_end
                    })

                if el_end.data("moduleCalendar") == undefined
                    el_end.calendar
                        ampm: false
                        inline: true
                        startCalendar: el_start

                $( '#'+id ).modal
                    onApprove: ->
                        start = new moment( el_start.calendar('get date') );
                        end   = new moment( el_end.calendar('get date') );
                        bus.$emit(
                            this.getAttribute('name')+'_changed',
                            this.getAttribute('id'),
                            start.format('YYYY-MM-DD HH:mm:ss') + ' | ' + end.format('YYYY-MM-DD HH:mm:ss')
                        );
                        console.log start.format('YYYY-MM-DD HH:mm:ss') + ' | ' + end.format('YYYY-MM-DD HH:mm:ss')

                $( '#'+id ).modal 'show'
</script>
