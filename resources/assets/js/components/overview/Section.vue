<template lang="pug">
    div.ui.vertical.fluid.menu
        a.item(
            v-for="section in sections"
            @click="selectSection(section)" ) {{ section.name }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Section'

        methods:
            selectSection: (section) ->
                bus.$emit 'section_changed',section

        props: [
            'sections'
        ]

</script>
