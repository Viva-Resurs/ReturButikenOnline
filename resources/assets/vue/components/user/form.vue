<template lang="pug">
    div#userForm
        div.ui.dividing.header {{ translate('user_form.header') }}
        div.ui.basic.segment
            user-card.centered(
                ":user"="user"
                "picture"="true"
                "type" = "vertical"
                "detailed"="true" )
        form.ui.form#user_form(
            v-if="user"
            "v-on:submit.prevent"="attemptSave" )
            div.ui.grid
                div.row.tablet.reversed.computer.reversed.stackable
                    div.eight.wide.column.right.floated
                        label {{ translate('user_form.profile_photo_label') }}
                        image-dropzone(
                            ":images"="user.images"
                            mode="usefirst" )
                    div.eight.wide.column.left.floated
                        div.field
                            label {{ translate('user_form.user_name_label') }}
                            input#name(
                                type="text"
                                "v-model"="user.name"
                                ":placeholder"="translate('user_form.user_name_placeholder')" )
                        div.field
                            div.ui.checkbox()
                                input#change_password(
                                    type="checkbox"
                                    v-model="settings.change_password" )
                                label.link( for="change_password" ) {{ translate('user_form.change_password_label') }}
                        div.field( v-if="settings.change_password" )
                            label {{ translate('user_form.password_label') }}
                            input#password(
                                type="password"
                                "v-model"="user.password"
                                placeholder="***" )
                div.row.stackable.equal.width
                    div.column
                        div.field( v-if="$root.isAdmin()" )                           
                            label {{ translate('user_form.select_role_label') }}
                            div.ui.fluid.selection.dropdown(
                                v-if="roles"
                                name="roles"
                                v-dropdown=""
                                ":data-selected"="selectedRoles" )
                                input#roles( type="hidden" )
                                div.default.text {{ translate('user_form.select_role_default_text') }}
                                i.dropdown.icon
                                div.menu
                                    div.item(
                                        v-for="role in roles"
                                        ":data-value"="role.id" )
                                        | {{ role.name }}
                    div.column(v-if="user.roles[0].name != 'admin'")                 
                        div.field( v-if="sections" )
                                label {{ translate('user_form.select_section_label') }}
                                div.ui.fluid.selection.dropdown(
                                    v-if="sections"
                                    name="sections"
                                    v-dropdown=""
                                    ":data-selected"="selectedSections" )
                                    input#sections( type="hidden" )
                                    div.default.text {{ translate('user_form.select_section_default_text') }}
                                    i.dropdown.icon
                                    div.menu
                                        div.item(
                                            v-for="section in sections"
                                            ":data-value"="section.id" )
                                            | {{ getLanguage() == 'sv' ? section.name_sv : section.name }}
                div.row.stackable
                    div.eight.wide.column
                        div.field
                            label {{ translate('user_form.fullname_label') }}
                            input#fullname(
                                type="text"
                                "v-model"="user.fullname"
                                ":placeholder"="translate('user_form.fullname_placeholder')" )
                    div.eight.wide.column
                        div.field
                            label {{ translate('user_form.phone_label') }}
                            input#phone(
                                type="text"
                                ":placeholder"="translate('user_form.phone_placeholder')"
                                "v-model"="user.phone" )
                div.sixteen.wide.column
                    div.field
                        label {{ translate('user_form.email_label') }}
                        input#email(
                            type="email"
                            "v-model"="user.email"
                            ":placeholder"="translate('user_form.email_placeholder')" )
                
                
            div.ui.divider
            div.ui.grid
                div.column.right.aligned 
                    div.ui.button.secondary(
                        @click="goBack()"
                    ) {{ translate('nav.back') }}
                    div.ui.button.primary(
                        type="submit"
                        @keydown.enter.prevent="attemptSave"
                        @click="attemptSave"
                    ) {{ translate('user_form.user_save') }}
</template>

<script lang="coffee">
    module.exports =
        name: 'UserForm'
        props: [ 'draft', 'roles', 'sections' ]
        components:
            ImageDropzone: require '../ImageDropzone.vue'
            UserCard: require './card.vue'
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
                
                if @user
                    # Apply current order if any
                    @user.images = @user.images.sort (a, b) => a.order-b.order
                                    
                    # Then set it
                    if @user.images
                        for image, index in @user.images
                            image.order = index
                    
                    if (@$root.user.id == @user.id)
                        @$root.user = @user   
                        
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
