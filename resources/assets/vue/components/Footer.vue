<template lang="pug">
    div.ui.container
        div.ui.text.menu
            div.right.text.menu
                div.item
                    div.ui.fluid.selection.dropdown#languages(
                        v-if="language_lib"
                        name="language"
                        v-dropdown=""
                        ":data-selected"="lang" )
                        input#lang( type="hidden" )
                        i.dropdown.icon
                        div.default.text
                            i.icon.translate.icon-style
                        div.menu
                            div.item(
                                v-for="language, key in language_lib"
                                ":data-value"="key" )
                                | {{ key }}&nbsp;&nbsp;
                                i.icon.translate.icon-style
</template>

<script lang="coffee">
    module.exports =
        name: 'Footer'
        props: [ 'language_lib', 'lang' ]
        created: ->
            # Listen for changes in Language
            bus.$on 'language_changed', (id, new_selection) =>
                @$root.setLang new_selection[0]
</script>
