<template lang="pug">
    div.ui.icon.button.hover-default(
        v-tooltip     = ""
        ":data-html"  = "displayInterval(item.bidding_interval)"
        ":data-value" = "item.bidding_interval"
        @click        = "showRangePicker"
    )
        i.ui.icon.legal( ":class" = `
            (activeInterval(item.bidding_interval)) ? 'active-interval':''
        ` )
</template>

<script lang="coffee">
    module.exports =
        name: 'BiddingInterval'
        props: [ 'item' ]
        methods:
            displayInterval: (interval) ->
                if interval.indexOf('|') < 0
                    return 'Click to set interval'
                dates = interval.split '|'
                if dates.length < 1
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
                item = @item
                bus.$emit 'show_message',
                    title:'Välj datum',
                    message:'',
                    start: item.bidding_interval.split('|')[0],
                    end: item.bidding_interval.split('|')[1]
                    type:'calendar',
                    cb: ( start, end ) ->
                        bus.$emit(
                            'bidding_interval_changed',
                            item.id,
                            start.format('YYYY-MM-DD HH:mm:ss') + ' | ' + end.format('YYYY-MM-DD HH:mm:ss')
                        )
</script>
