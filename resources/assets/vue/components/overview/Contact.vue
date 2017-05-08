<template lang="pug">
    div.ui.grid.padded
        div.ui.vertical.fluid.compact.menu.computer.tablet.only.row(style="padding-top: 0px !important")
            div.ui.grid.item.header(
                style="padding-top: 10px !important; padding-bottom: 4px !important; padding-left: 10px !important; padding-right: 6px !important")
                div.thirteen.wide.column(style="padding: 0px !important")
                    | {{ translate('contacts') }}
                div.ui.container.right.aligned.three.wide.column(style="padding: 0px !important; margin: 0px !important")
                    router-link( to="/ui/users" )
                        i.black.icon.external.square(
                            style="margin: 0px !important"
                            v-tooltip = ""
                            ":data-html" = "translate('goto.users')" )
                    router-link( to="/ui/users/create" )
                        i.black.plus.icon.square.right.floated(
                            style="margin: 0px !important"
                            v-tooltip = ""
                            ":data-html" = "translate('goto.createUser')" )
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
