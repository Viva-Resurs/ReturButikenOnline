<template lang="pug">
    div.ui.attached.vertical.menu
        a.item(v-for="contact in section.contacts" @click="selectContact(contact)") {{ contact.name }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Contact'

        methods:
            selectContact: (contact) ->
                bus.$emit 'contact_changed',contact

        props: [
            'section'
        ]

</script>
