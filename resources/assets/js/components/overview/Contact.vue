<template lang="pug">
    div.ui.vertical.fluid.menu
        div.item
            div.header Contacts
        a.item(
            v-for="contact in contacts"
            ":class"="contact.selected ? 'active':''"
            @click="selectContact(contact)" ) {{ contact.name }}
        i.item(
            v-if="contacts.length==0"
            ) empty...
</template>

<script lang="coffee">
    module.exports =
        name: 'Contact'
        props: [ 'contacts' ]
        methods:
            selectContact: (contact) ->
                bus.$emit 'contact_changed', contact
</script>
