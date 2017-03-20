<template lang="pug">
    div.ui.grid.padded
        div.ui.vertical.fluid.compact.menu.computer.tablet.only.row
            router-link.item.header( to="/users" )
                | {{ translate('contacts') }}
                i.icon.external.square(
                    v-tooltip = ""
                    ":data-html" = "translate('goto.users')" )
            a.item(
                v-for="contact in contacts"
                ":class"="contact.selected ? 'active':''"
                @click="selectContact(contact)" ) {{ contact.name }}
                div.ui.small.label(
                    ":class"="contact.articles.length>0 ? 'black' : 'grey'" )
                    | {{ contact.articles.length }}
            i.item( v-if="contacts.length==0" ) {{ translate('empty') }}
        div.ui.row.selection.dropdown.mobile.only#contacts(
            v-dropdown=""
            ":class"="contacts.length > 0 ? '' : 'disabled'" )
            input(type="hidden" name="contact_selection" )
            i.dropdown.icon
            div.default.text {{ translate('contacts') }}
            div.class.menu
                div.item(
                    v-for="contact in contacts"
                    @click="selectContact(contact)"
                    ":data-value"="contact.id" ) {{contact.name}}
</template>

<script lang="coffee">
    module.exports =
        name: 'Contact'
        props: [ 'contacts' ]
        methods:
            selectContact: (contact) ->
                bus.$emit 'contact_changed', contact
</script>
