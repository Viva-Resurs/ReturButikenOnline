<template lang="pug">
    div.ui.container.segment#userForm

        div.ui.dividing.header Användare

        form.ui.form(
            "v-on:submit.prevent"="attemptSave"
            role="form"
        )

            div.eight.wide.field
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

            div.eight.wide.field( v-if="settings.change_password" )
                label Lösenord:
                input#password(
                    type="password"
                    "v-model"="user.password"
                    placeholder="***"
                )

            div.eight.wide.field( v-if="$root.isAdmin()" )
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

            div.eight.wide.field( v-if="roles && $root.isAdmin()" )
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

            div.field
                div.two.fields
                    div.field
                        label Namn:
                        input(
                            type="text"
                            id="fullname"
                            "v-model"="user.fullname"
                            placeholder="Namn"
                        )
                    div.field
                        label Telefon:
                        input(
                            type="text"
                            id="phone"
                            placeholder="Telefon"
                            "v-model"="user.phone"
                        )

                label E-post:
                input#email(
                    type="email"
                    "v-model"="user.email"
                    placeholder="Epost"
                )

            div.field
                button.ui.right.floated.button.primary(
                    type="submit"
                    @keydown.enter.prevent="attemptSave"
                ) Spara

            br
            br
</template>

<script lang="coffee">
    module.exports =
        name: 'UserForm'
        props: [ 'original' ],
        data: -> {
            user:
                name: ''
                password: false
                email: ''
                fullname: false
                phone: ''
                selected_roles: []
                selected_sections: []
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
                this.$http.get('roles').then(
                    (response) =>
                        console.log 'roles ready'
                        this.roles = response.data;

                    (response) =>
                        bus.$emit('error', response);
                        this.roles = []

                );
            getSectionList: ->
                this.$http.get('sections').then(
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

            # Get roles
            @getRoleList()

            # Get sections
            @getSectionList()

</script>
