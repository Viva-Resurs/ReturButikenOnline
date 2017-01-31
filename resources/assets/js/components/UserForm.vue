<template lang="pug">
    div#userForm
        div.ui.dividing.header Användare
        div.ui.basic.segment
            div.ui.card.centered
                div.content
                    div.center.aligned.profile_img(
                        v-if="user.selected_images && user.selected_images[0]"
                        ":style"="'background-image:url(\"'+user.selected_images[0].path+'\");'" )
                    h2.ui.center.aligned.icon.header
                        i.circular.icon.user(
                            v-if="!user.selected_images || !user.selected_images[0]" )
                        | {{ user.fullname }}
                    div.description
                        p
                            i.icon.phone
                            b &nbsp;{{ user.phone }}
                        p
                            i.icon.mail
                            b {{ user.email }}
                        p( v-for="role in roles" )
                            span(
                                v-for="selected_role in user.selected_roles"
                                v-if="role.id == selected_role" )
                                i.icon.unlock.alternate
                                b {{ role.name }}
                        p( v-for="section in sections" )
                            span(
                                v-for="selected_section in user.selected_sections"
                                v-if="section.id == selected_section" )
                                i.icon.building
                                b {{ section.name }}
        form.ui.form#user_form(
            "v-on:submit.prevent"="attemptSave"
            role="form"
        )
            div.ui.grid
                div.row.tablet.reversed.computer.reversed.stackable
                    div.eight.wide.column.right.floated
                        label Profilfoto
                        image-dropzone(
                            ":images"="user.selected_images"
                            mode="usefirst" )
                    div.eight.wide.column.left.floated
                        div.field
                            label Användarnamn:
                            input#name(
                                type="text"
                                "v-model"="user.name"
                                placeholder="Användarnamn" )
                        div.field
                            div.ui.checkbox( v-checkbox="" )
                                input.hidden(
                                    type="checkbox"
                                    tabindex="0" value="0"
                                    v-model="settings.change_password" )
                                label Ändra lösenord
                        div.field( v-if="settings.change_password" )
                            label Lösenord:
                            input#password(
                                type="password"
                                "v-model"="user.password"
                                placeholder="***" )
                        div.field( v-if="$root.isAdmin()" )
                            label Välj roll:
                            div.ui.fluid.selection.dropdown#roles(
                                v-if="roles"
                                name="roles"
                                v-dropdown=""
                                ":data-selected"="(user.selected_roles.length) ? user.selected_roles[0].id : 0" )
                                input#validate_roles( type="hidden" )
                                div.default.text Select Roles
                                i.dropdown.icon
                                div.menu
                                    div.item(
                                        v-for="role in roles"
                                        ":data-value"="role.id" )
                                        | {{ role.name }}
                        div.field( v-if="roles && $root.isAdmin()" )
                            label Välj område:
                            div.ui.fluid.selection.dropdown#sections(
                                v-if="sections"
                                name="sections"
                                v-dropdown=""
                                ":data-selected"="(user.selected_sections.length) ? user.selected_sections[0].id : 0" )
                                input#validate_sections( type="hidden" )
                                div.default.text Select Sections
                                i.dropdown.icon
                                div.menu
                                    div.item(
                                        v-for="section in sections"
                                        ":data-value"="section.id" )
                                        | {{ section.name }}
                div.row
                    div.eight.wide.column
                        div.field
                            label Namn:
                            input#fullname(
                                type="text"
                                "v-model"="user.fullname"
                                placeholder="Namn" )
                    div.eight.wide.column
                        div.field
                            label Telefon:
                            input#phone(
                                type="text"
                                placeholder="Telefon"
                                "v-model"="user.phone" )
                div.sixteen.wide.column
                    div.field
                        label E-post:
                        input#email(
                            type="email"
                            "v-model"="user.email"
                            placeholder="Epost" )
                div.sixteen.wide.column
                    div.field
                        button.ui.right.floated.button.primary(
                            type="submit"
                            @keydown.enter.prevent="attemptSave" ) Spara
</template>

<script lang="coffee">
    module.exports =
        name: 'UserForm'
        props: [ 'original' ]
        components:
            ImageDropzone: require './ImageDropzone.vue'
        data: ->
            ready: false
            user:
                name: ''
                password: false
                email: ''
                fullname: ''
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
            form_settings:
                inline: true
                on: 'blur'
                onSuccess: =>
                    bus.$emit 'user_form_update', @user
                fields:
                    name:
                        identifier: 'name'
                        rules: [
                            type: 'empty'
                            prompt: 'Please enter your name'
                        ]
                    validate_roles:
                        identifier: 'validate_roles'
                        rules: [
                            type: 'empty'
                            prompt: 'Please select a role'
                        ]
                    validate_sections:
                        identifier: 'validate_sections'
                        rules: [
                            type: 'empty'
                            prompt: 'Please select a section'
                        ]
                    fullname:
                        identifier: 'fullname'
                        rules: [
                            type: 'empty'
                            prompt: 'Please enter your full name'
                        ]
                    phone:
                        identifer: 'phone'
                        rules: [
                            type: 'empty'
                            prompt: 'Please enter your phone number'
                        ]
                    email:
                        identifier: 'email'
                        rules: [
                            type: 'email'
                            prompt: 'Please enter your email-adress'
                        ]
        methods:
            attemptSave: ->
                if !@settings.change_password
                    @user.password = false
                # Validate form
                if !@ready
                    $('#user_form').form(@form_settings).form 'validate form'
                    @ready = true

            getRoleList: ->
                @$http.get('api/roles').then(
                    (response) =>
                        @roles = response.data
                    (response) =>
                        bus.$emit 'error', response
                        @roles = []
                )

            getSectionList: ->
                @$http.get('api/sections').then(
                    (response) =>
                        @sections = response.data
                    (response) =>
                        bus.$emit 'error', response
                        @sections = []
                )

        created: ->
            # If a original is passed (Update-mode), fill the form
            if @original
                @user = @original

            # Listen for changes in Roles
            bus.$on 'roles_changed', (id, new_value) =>
                @user.selected_roles = new_value

            # Listen for changes in Sections
            bus.$on 'sections_changed', (id, new_value) =>
                @user.selected_sections = new_value

            # Images
            bus.$on 'image_added', (image) =>
                @user.selected_images.push image
            bus.$on 'image_removed', (image) =>
                for index, img of @user.selected_images
                    if Number(img.id) == Number(image.id)
                        @user.selected_images.splice index, 1

            # Get roles
            @getRoleList()

            # Get sections
            @getSectionList()
</script>

<style lang="scss">
    .profile_img {
        background-size:cover;
        width:200px;
        height:200px;
        background-position: 50% 50%;
        border-radius: 100px;
        margin-left: auto;
        margin-right: auto;
    }
</style>
