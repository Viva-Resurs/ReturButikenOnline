<template lang="pug">
    div.field
        label {{interval}}
        div.ui.icon.input
            input(
                type='text'
                @click="openRangePicker"
                v-model="date"
                ":name"="interval"
                ":data-opens"="opens"
            )
            i.calendar.icon
</template>

<script lang="coffee">
    DateRangePicker = require '../mixins/DateRangePicker.vue'
    module.exports = {
        name: 'DateInterval'
        props: [ 'interval', 'date', 'opens' ]
        mixins: [ DateRangePicker ]
    }
</script>
