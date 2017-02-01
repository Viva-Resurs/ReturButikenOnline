<template lang="pug">
div.ui.card
    div.content
        div.center.aligned.profile_img(
            v-if="user.selected_images && user.selected_images[0] && (detailed != undefined)"
            ":style"="'background-image:url(\"'+user.selected_images[0].path+'\");'" )
        h2.ui.center.aligned.icon.header
            i.circular.icon.user(
                v-if="(!user.selected_images || !user.selected_images[0]) && (detailed != undefined)" )
            | {{ user.fullname }}
        div.description
            p
                i.icon.phone
                b &nbsp;{{ user.phone }}
            p
                i.icon.mail
                b {{ user.email }}
            p( v-for="role in roles" v-if="detailed != undefined" )
                span(
                    v-for="selected_role in user.selected_roles"
                    v-if="role.id == selected_role" )
                    i.icon.unlock.alternate
                    b {{ role.name }}
            p( v-for="section in sections" v-if="detailed != undefined" )
                span(
                    v-for="selected_section in user.selected_sections"
                    v-if="section.id == selected_section" )
                    i.icon.building
                    b {{ section.name }}
</template>
<script lang="coffee">
    module.exports =
        name: 'UserCard'
        props: ['user', 'roles', 'sections', 'detailed']
</script>
