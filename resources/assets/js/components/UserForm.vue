<template lang="pug">
    div.ui.container.segment#userForm

        div.ui.dividing.header Användare

        div.ui.basic.segment
            div.ui.card.centered
                div.content
                    div.image.center.aligned( v-if="user.selected_images && user.selected_images[0]" )
                        img.ui.circular.image( ":src"="'/'+user.selected_images[0].path" )
                    h2.ui.center.aligned.icon.header
                        i.circular.icon.user( v-if="!user.selected_images || !user.selected_images[0]" )
                        | {{ user.fullname }}
                    div.description
                        p
                            i.icon.phone
                            b &nbsp;{{ user.phone }}
                        p
                            i.icon.mail
                            b {{ user.email }}
                        p(v-for="role in roles")
                            span(v-for="selected_role in user.selected_roles"
                                v-if="role.id == selected_role")
                                i.icon.unlock.alternate
                                b {{ role.name }}
                        p(v-for="section in sections")
                            span(v-for="selected_section in user.selected_sections"
                                v-if="section.id == selected_section")
                                i.icon.building
                                b {{ section.name }}


        form.ui.form(
            "v-on:submit.prevent"="attemptSave"
            role="form"
        )
            div.ui.grid
                div.row.tablet.reversed.computer.reversed.stackable
                    div.eight.wide.column.right.floated
                        label Profilfoto
                        image-dropzone( ":images"="user.selected_images" mode="usefirst" )

                    div.eight.wide.column.left.floated
                        div.field
                            label Användarnamn:
                            input#name(
                                type="text"
                                "v-model"="user.name"
                                placeholder="Användarnamn"
                            )

                        div.field
                            div.ui.checkbox( v-checkbox="" )
                                input.hidden(
                                    type="checkbox"
                                    tabindex="0" value="0"
                                    v-model="settings.change_password"
                                )
                                label Ändra lösenord

                        div.field( v-if="settings.change_password" )
                            label Lösenord:
                            input#password(
                                type="password"
                                "v-model"="user.password"
                                placeholder="***"
                            )

                        div.field( v-if="$root.isAdmin()" )
                            label Välj roll:
                            div.ui.fluid.selection.dropdown#role(
                                v-if="roles"
                                name="roles"
                                v-dropdown=""
                                ":data-selected"="(user.selected_roles.length) ? user.selected_roles[0].id : user.selected_roles"
                            )
                                i.dropdown.icon
                                div.default.text Select Roles
                                div.menu
                                    div.item(
                                        v-for="role in roles"
                                        ":data-value"="role.id"
                                    ) {{role.name}}

                        div.field( v-if="roles && $root.isAdmin()" )
                            label Välj område:
                            div.ui.fluid.selection.dropdown#role(
                                name="sections"
                                v-if="sections"
                                v-dropdown=""
                                ":data-selected"="(user.selected_sections.length) ? user.selected_sections[0].id : user.selected_sections"
                            )
                                i.dropdown.icon
                                div.default.text Select Sections
                                div.menu
                                    div.item(
                                        v-for="section in sections"
                                        ":data-value"="section.id"
                                    ) {{section.name}}

                div.row
                    div.eight.wide.column
                        div.field
                            label Namn:
                            input(
                                type="text"
                                id="fullname"
                                "v-model"="user.fullname"
                                placeholder="Namn"
                            )
                    div.eight.wide.column
                        div.field
                            label Telefon:
                            input(
                                type="text"
                                id="phone"
                                placeholder="Telefon"
                                "v-model"="user.phone"
                            )
                div.sixteen.wide.column
                    div.field
                        label E-post:
                        input#email(
                            type="email"
                            "v-model"="user.email"
                            placeholder="Epost"
                        )

                div.sixteen.wide.column
                    div.field
                        button.ui.right.floated.button.primary(
                            type="submit"
                            @keydown.enter.prevent="attemptSave"
                        ) Spara

            br
            br
</template>

<script lang="coffee">
    ImageDropzone = require './ImageDropzone.vue'
    module.exports =
        name: 'UserForm'
        props: [ 'original' ],
        components: { ImageDropzone },
        data: -> {
            user:
                name: ''
                password: false
                email: ''
                fullname: false
                phone: ''
                selected_roles: []
                selected_sections: []
                selected_images: []
            roles:
                false
            sections:
                false
            settings:
                change_password: false
            myform: []
        }

        methods:
            attemptSave: ->
                if (!@settings.change_password)
                    @user.password = false;
                bus.$emit( 'user_form_update', this.user );

            getRoleList: ->
                this.$http.get('/api/roles').then(
                    (response) =>
                        console.log 'roles ready'
                        this.roles = response.data;

                    (response) =>
                        bus.$emit('error', response);
                        this.roles = []

                );
            getSectionList: ->
                this.$http.get('/api/sections').then(
                    (response) =>
                        this.sections = response.data;

                    (response) =>
                        bus.$emit('error', response);
                        this.sections = []

                );

        created: ->
            # If a original is passed (Update-mode), fill the form
            if (this.original)
                this.user = this.original;

            # Listen for changes in Roles
            bus.$on('roles_changed', (id,new_value) =>
                this.user.selected_roles = new_value;
            );

            # Listen for changes in Sections
            bus.$on('sections_changed', (id,new_value) =>
                this.user.selected_sections = new_value;
            );

            # Images
            bus.$on('image_added', (image) =>
                console.log image
                this.user.selected_images.push(image)
            )
            bus.$on('image_removed', (image) =>
                console.log image
                for index, img of @user.selected_images
                    if (Number img.id == Number image.id)
                        this.user.selected_images.splice(index,1)
            )

            # Get roles
            @getRoleList()

            # Get sections
            @getSectionList()

</script>
