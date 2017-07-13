<template lang="pug">
    
    div.ui.grid.padded.start-item        
        div.ui.vertical.fluid.compact.secondary.pointing.menu.computer.tablet.only.row#overview_section_tablet_menu
            div.ui.grid.item.header
                div.twelve.wide.column
                    | {{ translate('section.header') }}
                    div.ui.tiny.green.label#overview_section_numsections(v-if="sections.length > 1") {{ sections.length }}
                div.ui.container.right.aligned.four.wide.column
                    router-link( to="/ui/sections" )
                        i.icon.external.icon-style(
                            v-tooltip = ""
                            ":data-html" = "translate('goto.sections')" )                    
            
            div.ui.divider#overview_section_divider
            div(":id"="sections.length > 3 ? 'overview_section_list' : ''")
                a.item(
                    v-for="section in sections"
                    ":class"="section.selected ? 'active':''"
                    @click="selectSection(section)" ) {{ getLanguage() == 'sv' ? section.name_sv : section.name }}
                i.item( v-if="sections.length==0" ) {{ translate('empty') }} 
        
        div.ui.fluid.row.mobile.only.no-padding
            div.ui.fluid.label#overview_section_mobile_header {{ translate('sections') }}
            div.ui.fluid.selection.label.dropdown#sections(
                v-dropdown=""
                ":class"="sections.length > 0 ? '' : 'disabled'" )
                input(type="hidden" name="section_selection" )
                i.dropdown.icon
                div.default.text {{ translate('sections') }}
                div.class.menu
                    div.item(
                        v-for="section in sections"
                        @click="selectSection(section)"
                        ":data-value"="section.id" ) {{section.name}}
</template>

<script lang="coffee">
    module.exports =
        name: 'Section'
        props: [ 'sections' ]
        methods:
            ###*
            #   Emits section change
            #   @param {section} section that have changed
            ###
            selectSection: (section) ->
                bus.$emit 'section_changed', section
</script>

<style>
    #overview_section_list {        
        max-height: 120px; 
        overflow-y: auto;             
        padding-right: 2px;
    }

    #overview_section_divider {
        margin-top: 0px; 
        margin-bottom: 0px;    
    }
    
    #overview_section_tablet_menu {
        height: 100%;
    }

    #overview_section_mobile_header {
        border-radius: 0px; 
        margin-right: 0px;
    }

    #overview_section_tablet_menu.ui.secondary.vertical.pointing.menu {
        border-right-style: none;        
    }

    #overview_section_step.column.step.right.floated {
        border-right: 2px solid !important; 
        border-right-color: rgba(34,36,38,.15) !important;
    }

    #sections {
        border-radius: 0px;
        background-color: white; 
        margin-left: 0px
    }

    #overview_section_numsections {
        position: absolute; 
        top: -3px;
    }

</style>