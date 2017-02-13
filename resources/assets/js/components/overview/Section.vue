<template lang="pug">
    div.ui.vertical.fluid.menu
        div.item
            div.header Sections
        a.item(
            v-for="section in sections"
            ":class"="section.selected ? 'active':''"
            @click="selectSection(section)" ) {{ section.name }}
            div.ui.tiny.label(
                ":class"="section.contacts.length>0 ? 'black' : 'grey'" )
                | {{ section.contacts.length }}
        i.item(
            v-if="sections.length==0"
            ) empty...
</template>

<script lang="coffee">
    module.exports =
        name: 'Section'
        props: [ 'sections' ]
        methods:
            selectSection: (section) ->
                bus.$emit 'section_changed', section
</script>
