<template lang="pug">
    div#userForm
        div.ui.dividing.header Användare
        div.ui.basic.segment
            user-card.centered(
                ":user"="user"
                "picture"="true"
                "detailed"="true" )
        form.ui.form#user_form(
            v-if="user"
            "v-on:submit.prevent"="attemptSave" )
            div.ui.grid
                div.row.tablet.reversed.computer.reversed.stackable
                    div.eight.wide.column.right.floated
                        label Profilfoto
                        image-dropzone(
                            ":images"="user.images"
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
                            div.ui.fluid.selection.dropdown(
                                v-if="roles"
                                name="roles"
                                v-dropdown=""
                                ":data-selected"="selectedRoles" )
                                input#roles( type="hidden" )
                                div.default.text Select Roles
                                i.dropdown.icon
                                div.menu
                                    div.item(
                                        v-for="role in roles"
                                        ":data-value"="role.id" )
                                        | {{ role.name }}
                        div.field( v-if="roles && $root.isAdmin()" )
                            label Välj område:
                            div.ui.fluid.selection.dropdown(
                                v-if="sections"
                                name="sections"
                                v-dropdown=""
                                ":data-selected"="selectedSections" )
                                input#sections( type="hidden" )
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
                    div.ui.container
                        div.ui.hidden.divider
                        div.ui.container.right.aligned
                            div.ui.button.secondary(
                                @click="goBack()"
                            ) Back
                            div.ui.button.primary(
                                type="submit"
                                @keydown.enter.prevent="attemptSave"
                                @click="attemptSave"
                            ) Spara
</template>

<script lang="coffee">
    module.exports =
        name: 'UserForm'
        props: [ 'draft', 'roles', 'sections' ]
        components:
            ImageDropzone: require './ImageDropzone.vue'
            UserCard: require './UserCard.vue'
        data: ->
            user: false
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
                        identifier: 'roles'
                        rules: [
                            type: 'empty'
                            prompt: 'Please select a role'
                        ]
                    validate_sections:
                        identifier: 'sections'
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
        computed:
            selectedRoles: ->
                results = ''
                for role, index in @user.roles
                    results += role.id
                    if index < @user.roles.length
                        results += ','
                return results
            selectedSections: ->
                results = ''
                for section, index in @user.sections
                    results += section.id
                    if index < @user.sections.length
                        results += ','
                return results
        methods:
            updateImageOrder: ->
                # Apply current order if any
                @user.images = @user.images.sort (a, b) => a.order-b.order
                # Then set it
                for image, index in @user.images
                    image.order = index
            attemptSave: ->
                # Skip password?
                if !@settings.change_password
                    @user.password = false
                # Validate form
                $('#user_form').form(@form_settings).form 'validate form'
        created: ->
            # Get the form ready
            @user = @draft
            # Refresh Image ordering
            @updateImageOrder()
            # Listen for changes in Roles
            bus.$on 'roles_changed', (id, selection) =>
                Vue.set @user, 'roles', []
                for role in @roles
                    for selected in selection
                        if Number(role.id) == Number(selected)
                            @user.roles.push role
            # Listen for changes in Sections
            bus.$on 'sections_changed', (id, selection) =>
                Vue.set @user, 'sections', []
                for section in @sections
                    for selected in selection
                        if Number(section.id) == Number(selected)
                            @user.sections.push section
            # Listen for changes in Images
            bus.$on 'image_added', (image) =>
                image.order = @user.images.length
                @user.images.push image
                @updateImageOrder()
            bus.$on 'image_removed', (image) =>
                for index, img of @user.images
                    if Number(img.id) == Number(image.id)
                        @user.images.splice index, 1
                @updateImageOrder()
            bus.$on 'image_reorder', =>
                @updateImageOrder()
        beforeDestroy: ->
            bus.$off 'roles_changed'
            bus.$off 'sections_changed'
            bus.$off 'image_added'
            bus.$off 'image_removed'
            bus.$off 'image_reorder'
</script>
