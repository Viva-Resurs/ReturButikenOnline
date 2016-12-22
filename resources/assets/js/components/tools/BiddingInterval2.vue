<template lang="pug">
    div
        div.ui.icon.button.hover-default(
            v-tooltip     = ""
            ":data-html"  = "displayInterval(item.bidding_interval)"
            name          = "Bidding Interval"
            ":id"         = "item.id"
            ":data-value" = "item.bidding_interval"
            @click        = "showRangePicker"
        )
            i.ui.icon.legal( ":class" = `
                (activeInterval(item.bidding_interval)) ? 'active-interval':''
            ` )

        div.ui.basic.modal(":id"='biddingId()')
            div.header Bidding interval
            div.ui.segment
                div.ui.form
                    div.two.stackable.fields
                        div.field
                            h4.ui.sub.header Start date
                            div.ui.small.calendar#rangestart
                                div.ui.input.left.icon
                                    i.calendar.icon
                                    input( type="text" placeholder="Start" )

                        div.field
                            h4.ui.sub.header End date
                            div.ui.calendar#rangeend
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

        methods:
            biddingId: () ->
                return 'bidding_'+@item.id;

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

            showRangePicker: () ->

                if ($('#rangestart').data("moduleCalendar") == undefined)
                    $('#rangestart').calendar({
                      ampm: false,
                      inline: true,
                      endCalendar: $('#rangeend')
                    });

                if ($('#rangeend').data("moduleCalendar") == undefined)
                    $('#rangeend').calendar({
                      ampm: false,
                      inline: true,
                      startCalendar: $('#rangestart')
                    });

                modalContainer = '#'+'bidding_'+@item.id
                $(modalContainer).modal('show')
</script>
