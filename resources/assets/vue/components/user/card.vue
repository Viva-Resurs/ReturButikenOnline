<template lang="pug">
    div
        div.ui.centered.card( v-if="type == 'vertical'")
            div.content
                div.center.aligned.profile_img(
                    v-if="user.images && user.images[0] && (picture != undefined)"
                    ":style"="'background-image:url(\"'+user.images[0].path+'\");'" )
                h2.ui.center.aligned.icon.header(v-if="!picture && picture == 'above'")
                    i.circular.icon.user(
                        v-if="!user.images || !user.images[0]" )
                    | {{ user.fullname }}
                h4.ui.header(v-else)
                    | {{ user.fullname }}
                div.description
                    div.ui.items
                        div.item
                            div.content
                                div.meta
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
        div.ui.segment( v-if="type == 'horizontal'")
            div.ui.items
                div.item
                    div.content
                        h4.ui.header
                            | {{ user.fullname }}
                        
                        div.right.floated.profile_img_right(
                                v-if="user.images && user.images[0] && (picture != undefined)"
                                ":style"="'background-image:url(\"'+user.images[0].path+'\");'" )
                        div.meta
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
        
        
        div.ui.grid.equal.width(v-if="picture == 'picture_only' && user.images && user.images[0]")
            div.column.tablet.computer.only
                div.ui.basic.segment.right.floated                                
                    div.profile_img_right(
                        ":style"="'background-image:url(\"'+user.images[0].path+'\");'" )                
            div.column.mobile.only
                div.profile_img_medium(
                    ":style"="'background-image:url(\"'+user.images[0].path+'\");'" )

</template>
<script lang="coffee">
    module.exports =
        name: 'UserCard'
        props: ['user', 'type', 'picture', 'detailed']
</script>
