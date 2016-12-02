<template lang="pug">
    div.ui.container.segment#userForm

        div.ui.dividing.header Användare

        form.ui.form(
            "v-on:submit.prevent"="attemptSave"
            role="form"
        )

            div.fields
                div.twelve.wide.field
                    label Användarnamn:
                    input#name(
                        type="text"
                        "v-model"="user.name"
                        placeholder="Varunamn"
                    )
                div.four.wide.field
                    label Välj roll:
                    div.ui.fluid.selection.dropdown#role(
                        v-if="roles"
                        name="roles"
                        v-dropdown=""
                        ":data-selected"="user.selected_roles"
                    )
                        i.dropdown.icon
                        div.default.text Select Roles
                        div.menu
                            div.item(
                                v-for="role in roles"
                                ":data-value"="role.id"
                            ) {{role.name}}

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

            div.field
                div.two.fields
                    div.field
                        label Namn:
                        input(
                            type="text"
                            id="fullname"
                            "v-model"="user.fullName"
                            placeholder="Ditt namn"
                        )
                    div.field
                        label Telefon:
                        input(
                            type="text"
                            id="phone"
                            placeholder="Ditt nummer"
                            "v-model"="user.phone"
                        )

                label E-post:
                input#email(
                    type="email"
                    "v-model"="user.email"
                    placeholder="Din epost"
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
        name: 'ArticleForm'
        props: [ 'original' ],
        data: -> {
            user:
                name: ''
                password: false
                email: ''
                fullName: false
                phone: ''
                selected_roles: []
            roles:
                null
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
                        this.roles = response.data;

                    (response) =>
                        bus.$emit('error', response);
                        this.roles = []

                );

        created: ->
            # If a original is passed (Update-mode), fill the form
            if (this.original)
                this.user = this.original;

            # Listen for changes in Roles
            bus.$on('roles_changed', (id,new_value) =>
                this.user.selected_roles = new_value;
            );

            # Get roles
            @getRoleList()

</script>
