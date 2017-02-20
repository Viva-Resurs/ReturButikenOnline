<template lang="pug">
div.ui.grid.padded
    div.ui.vertical.fluid.menu.computer.tablet.only.row
        div.item
            div.header Contacts
        a.item(
            v-for="contact in contacts"
            ":class"="contact.selected ? 'active':''"
            @click="selectContact(contact)" ) {{ contact.name }}
            div.ui.small.label(
                ":class"="contact.articles.length>0 ? 'black' : 'grey'" )
                | {{ contact.articles.length }}
        i.item(
            v-if="contacts.length==0"
            ) empty...

    div.ui.row.selection.dropdown.mobile.only(v-dropdown="" ":class"="contacts.length > 0 ? '' : 'disabled'" )
        input(type="hidden" name="section_selection")
        i.dropdown.icon
        div.default.text Contacts
        div.class.menu
            div.item(v-for="contact in contacts" @click="selectContact(contact)") {{contact.name}}
</template>

<script lang="coffee">
    module.exports =
        name: 'Contact'
        props: [ 'contacts' ]
        methods:
            selectContact: (contact) ->
                bus.$emit 'contact_changed', contact
</script>
