<template lang="pug">
    div.form-group
        div.row
            div.col-xs-12
                label.control-label( for="fullname" ) {{interval}}
                div.ui.icon.input
                    input.form-control(
                        type='text'
                        @click="openRangePicker"
                        v-model="date"
                        "name"="interval"
                    )
                    i.calendar.icon
</template>

<script lang="coffee">
    DateRangePicker = require '../mixins/DateRangePicker.vue';
    module.exports = {
        name: 'DateInterval'
        props: [ 'interval', 'date' ]
        mixins: [ DateRangePicker ]
    }
</script>

<style lang="css">
    .form-group {
        margin: 15px;
    }
</style>
