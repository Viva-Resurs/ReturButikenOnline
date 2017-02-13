<template lang="pug">
    div.ui.vertical.fluid.menu
        a.item(
            v-for="contact in contacts"
            @click="selectContact(contact)" ) {{ contact.name }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Contact'
        props: [ 'contacts' ]
        methods:
            selectContact: (contact) ->
                bus.$emit 'contact_changed',contact
</script>
