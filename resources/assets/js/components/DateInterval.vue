<template lang="pug">
    div.field
        label( ":for"="interval" ) {{interval}}
        div.ui.icon.input
            input(
                type='text'
                @click="openRangePicker"
                v-model="date"
                ":name"="interval"
            )
            i.calendar.icon
</template>

<script lang="coffee">
    DateRangePicker = require '../mixins/DateRangePicker.vue'
    module.exports = {
        name: 'DateInterval'
        props: [ 'interval', 'date' ]
        mixins: [ DateRangePicker ]
    }
</script>
