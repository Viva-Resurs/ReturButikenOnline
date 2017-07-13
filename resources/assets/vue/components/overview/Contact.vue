<template lang="pug">
    div.ui.grid.padded.start-item
        div.ui.vertical.fluid.compact.secondary.pointing.menu.computer.tablet.only.row#overview_contact_tablet_menu
            div.ui.grid.item.header
                div.twelve.wide.column
                    | {{ translate('contacts') }} 
                    div.ui.tiny.green.label#overview_contact_numcontacts(v-if="contacts.length > 1") {{ contacts.length }}
                div.ui.container.right.aligned.four.wide.column
                    router-link( to="/ui/users" )
                        i.icon.external.icon-style(
                            v-tooltip = ""
                            ":data-html" = "translate('goto.users')" )
                    router-link( to="/ui/users/create" )
                        i.plus.icon.square.outline.right.floated.icon-style(
                            v-tooltip = ""
                            ":data-html" = "translate('goto.createUser')" )

            div.ui.divider#overview_contact_divider
            div(":id"="contacts.length > 3 ? 'overview_contact_list' : ''")
                a.item(
                    v-for="contact in contacts"
                    ":class"="contact.selected ? 'active':''"
                    @click="selectContact(contact)" ) {{ contact.name }}                
                i.item( v-if="contacts.length==0" ) {{ translate('empty') }}
        
        div.ui.fluid.row.mobile.only.no-padding
            div.ui.fluid.label#overview_contact_mobile_header {{ translate('contacts') }}
            div.ui.fluid.selection.label.dropdown#contacts(    
                v-dropdown=""
                ":class"="contacts.length > 0 ? '' : 'disabled'" )
                input(type="hidden" name="contact_selection" )
                i.dropdown.icon
                div.default.text {{ translate('contacts') }}
                div.class.menu
                    div.item(
                        v-for="contact in contacts"
                        @click="selectContact(contact)"
                        ":data-value"="contact.id" ) {{contact.name}}



</template>

<script lang="coffee">
    module.exports =
        name: 'Contact'
        props: [ 'contacts' ]
        methods:
            ###*
            #   Emits contact change
            #   @param {contact} contact that have changed
            ###
            selectContact: (contact) ->
                bus.$emit 'contact_changed', contact
</script>

<style>
    #overview_contact_list {  
        max-height: 120px;  
        overflow-y: auto;        
        padding-right: 2px;
    }

    #overview_contact_divider {
        margin-top: 0px; 
        margin-bottom: 0px;         
    }

    #overview_contact_tablet_menu {
        height: 100%;
    }

    #overview_contact_mobile_header {
        border-radius: 0px; 
        margin-right: 0px;
    }

    #overview_contact_tablet_menu.ui.secondary.vertical.pointing.menu {
        border-right-style: none;
    }

    #contacts {
        border-radius: 0px;
        background-color: white; 
        margin-left: 0px;
    }

    #overview_contact_numcontacts {
        position: absolute; 
        top: -3px;
    }

    
</style>
