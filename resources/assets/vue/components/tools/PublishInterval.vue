<template lang="pug">
    div.ui.icon.button.hover-default(
        style="background-color: #FFF; border-top: 1px solid rgb(212,212,213); border-bottom: 1px solid rgb(212,212,213); border-left: 1px solid rgb(212,212,213)"
        v-tooltip     = ""
        ":data-html"  = "displayInterval(item.publish_interval)"
        ":data-value" = "item.publish_interval"
        @click        = "showRangePicker"
        ":class" = `
            (activeInterval(item.bidding_interval)) ? 'active-interval':''
        `
    )
        i.ui.icon.time.icon-style
</template>

<script lang="coffee">
    module.exports =
        name: 'PublishInterval'
        props: [ 'item' ]
        methods:
            displayInterval: (interval) ->
                if interval.indexOf('|') < 0
                    return @$root.translate('publish_interval.select_interval_message')
                dates = interval.split '|'
                if dates.length < 2
                    return false
                return "<b>"+@$root.translate('display_interval.start')+"</b><br>" + dates[0] + "<br><br>"+
                       "<b>"+@$root.translate('display_interval.end')+"</b><br>" + dates[1]

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
                    title: @$root.translate('publish_interval.select_date_title'),
                    message:'',
                    start: item.publish_interval.split('|')[0],
                    end: item.publish_interval.split('|')[1]
                    type:'calendar',
                    cb: ( start, end ) ->
                        bus.$emit(
                            'publish_interval_changed',
                            item.id,
                            start.format('YYYY-MM-DD HH:mm:ss') + ' | ' + end.format('YYYY-MM-DD HH:mm:ss')
                        )
</script>
