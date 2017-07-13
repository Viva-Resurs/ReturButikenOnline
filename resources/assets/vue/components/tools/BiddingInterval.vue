<template lang="pug">
    div.ui.icon.button.hover-default#tool_bidding_interval_button(        
        v-tooltip     = ""
        ":data-html"  = "displayInterval(item.bidding_interval)"
        ":data-value" = "item.bidding_interval"
        @click        = "showRangePicker"
        ":class" = `
            (activeInterval(item.bidding_interval)) ? 'active-interval':''
        `
    )
        i.ui.icon.legal.icon-style
</template>

<script lang="coffee">
    module.exports =
        name: 'BiddingInterval'
        props: [ 'item' ]
        methods:
            ###*
            #  Translates a interval into a formatted interval separated by newline.             
            #   @param {interval} original string
            #   @return {string} translated string
            ###
            displayInterval: (interval) ->
                if interval.indexOf('|') < 0
                    return @$root.translate('bidding_interval.select_interval_message')
                dates = interval.split '|'
                if dates.length < 2
                    return false
                return "<b>"+@$root.translate('display_interval.start')+"</b><br>" + dates[0] + "<br><br>"+
                       "<b>"+@$root.translate('display_interval.end')+"</b><br>" + dates[1]

            ###*
            #   Checks if a intervals enddate is before current date. 
            #   @param {interval} interval to check
            #   @return {boolean} true if it is before current date, otherwise false
            ###
            activeInterval: (interval) ->
                if (
                    interval == '' ||
                    typeof interval != 'string' ||
                    interval.split('|')[1] == ''
                )
                    return false
                return moment().isBefore interval.split('|')[1]

            ###*
            #   Displays a calendar for selecting start and end date.
            ###
            showRangePicker: ->
                item = @item
                bus.$emit 'show_message',
                    title: @$root.translate('bidding_interval.select_date_title'),
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

<style>
    #tool_bidding_interval_button {
        background-color: #FFF; 
        border: 1px solid rgb(212,212,213);
    }
</style>
