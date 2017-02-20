<template lang="pug">
    div.ui.grid.padded
        div.ui.vertical.fluid.menu.computer.tablet.only.row
            div.item
                div.header Sections
            a.item(
                v-for="section in sections"
                ":class"="section.selected ? 'active':''"
                @click="selectSection(section)" ) {{ section.name }}
                div.ui.small.label(
                    ":class"="section.contacts.length>0 ? 'black' : 'grey'" )
                    | {{ section.contacts.length }}
            i.item(
                v-if="sections.length==0"
                ) empty...
        div.ui.row.selection.dropdown.mobile.only(v-dropdown="" ":class"="sections.length > 0 ? '' : 'disabled'" )
            //input(type="hidden" name="section_selection")
            i.dropdown.icon
            div.default.text Sections
            div.class.menu
                div.item(v-for="section in sections" @click="selectSection(section)" ":class"="section.selected ? 'active':''") {{section.name}}
</template>

<script lang="coffee">
    module.exports =
        name: 'Section'
        props: [ 'sections' ]
        methods:
            selectSection: (section) ->
                bus.$emit 'section_changed', section
                @selected_contact = section.contacts[0]
                bus.$emit 'contact_changed', section.contacts[0]

</script>
