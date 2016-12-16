<template lang="pug">
    div.ui.icon.button.hover-default(
        v-tooltip     = ""
        ":data-html"  = "displayInterval(item.publish_interval)"
        name          = "publish_interval"
        ":id"         = "item.id"
        ":data-value" = "item.publish_interval"
        @click        = "openRangePicker"
    )
        i.ui.icon.dollar( ":class" = `
            (activeInterval(item.publish_interval)) ? 'active-interval':''
        ` )
</template>

<script lang="coffee">
    DateRangePicker = require '../../mixins/DateRangePicker.vue'
    module.exports =
        name: 'PublishInterval'
        props: [ 'item' ]
        mixins: [ DateRangePicker ]
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
</script>
