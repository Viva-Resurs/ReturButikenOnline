<template lang="pug">
div.ui.card
    div.content
        div.center.aligned.profile_img(
            v-if="user.images && user.images[0] && (picture != undefined)"
            ":style"="'background-image:url(\"'+user.images[0].path+'\");'" )
        h2.ui.center.aligned.icon.header(v-if="picture != undefined")
            i.circular.icon.user(
                v-if="(!user.images || !user.images[0]) && (picture != undefined)" )
            | {{ user.fullname }}

        h4.ui.header(v-else)
            | {{ user.fullname }}

        div.description
            p
                i.icon.phone
                b &nbsp;{{ user.phone }}
            p
                i.icon.mail
                b {{ user.email }}

            div(v-if="detailed != undefined")
                p( v-for="role in user.roles")
                    span
                        i.icon.unlock.alternate
                        b {{ role.name }}

                p( v-for="section in user.sections")
                    span
                        i.icon.building
                        b {{ section.name }}
</template>
<script lang="coffee">
    module.exports =
        name: 'UserCard'
        props: ['user','picture','detailed']
</script>
