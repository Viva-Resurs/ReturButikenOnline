<template lang="pug">
    div#articleForm
        div.ui.dividing.header Publicera
        form.ui.form#article_form(
            "v-on:submit.prevent"="previewArticle"
            role="form" )
            div.fields
                div.twelve.wide.field
                    label Varunamn:
                    input#name(
                        type="text"
                        "v-model"="article.name"
                        placeholder="Varunamn" )
                div.four.wide.field
                    label Välj varukategori:
                    div.ui.fluid.multiple.selection.dropdown#category(
                        v-if="categories"
                        name="categories"
                        v-dropdown=""
                        ":data-selected"="article.selected_categories" )
                        input#validate_categories( type="hidden" )
                        div.default.text Select Category
                        i.dropdown.icon
                        div.menu
                            div.item(
                                v-for="category in categories"
                                ":data-value"="category.id" )
                                | {{category.name}}
            div.field
                label Beskrivning av varan:
                textarea#desc(
                    rows="4"
                    v-model="article.desc"
                    placeholder="Ge din beskrivning här" )
            div.field
                label Pris:
                input#price(
                    type="number"
                    v-model="article.price"
                    placeholder="Ange ditt pris här" )
            div.field
                label Bilder:
                image-dropzone( ":images"="article.images" )
            div.two.fields
                div.field
                    div.ui.checkbox( v-checkbox="" )
                        input.hidden(
                            type="checkbox"
                            tabindex="0" value="0"
                            v-model="settings.publish_interval" )
                        label Publicera inom datumintervall
                div.field
                    div.ui.checkbox( v-checkbox="" )
                        input.hidden(
                            type="checkbox"
                            tabindex="1" value="1"
                            v-model="settings.bidding_interval" )
                        label Aktivera budgivning
            div.two.fields
                div.field( v-show="settings.publish_interval" )
                    h4.ui.sub.header publish_interval
                    div.ui.input.left.icon
                        i.calendar.icon
                        input(
                            type="text"
                            name="publish_interval"
                            placeholder="????-??-??"
                            @click="showRangePicker"
                            v-model="article.publish_interval" )
                div.field( v-show="settings.bidding_interval" )
                    h4.ui.sub.header bidding_interval
                    div.ui.input.left.icon
                        i.calendar.icon
                        input(
                            type="text"
                            name="bidding_interval"
                            placeholder="????-??-??"
                            @click="showRangePicker"
                            v-model="article.bidding_interval" )
            div.two.fields
                div.field
                    div.ui.radio.checkbox( v-checkbox="" )
                        input.hidden(
                            type="radio"
                            name="public"
                            tabindex="0" value="0"
                            v-model="article.public" )
                        label Publicera på kommunens Intranät
                div.field
                    div.ui.radio.checkbox( v-checkbox="" )
                        input.hidden(
                            type="radio"
                            name="public"
                            tabindex="1" value="1"
                            v-model="article.public" )
                        label Publicera för allmänheten
            div.ui.divider
            div.two.fields( v-if="contacts" )
                div.field
                    label Kontakt:
                    div.field( v-if="article.selected_contacts" )
                        user-card.fluid(
                            ":user"="contact"
                            ":roles"="contact.roles"
                            ":sections"="contact.sections"
                            "detailed"="true"
                            v-for="contact in selectedContacts" )
                div.field( v-if="article.selected_contacts && contacts.length>1" )
                    label Välj kontakt:
                    div.ui.fluid.selection.dropdown#contact(
                        v-if="contacts"
                        name="contacts"
                        v-dropdown=""
                        ":data-selected"="article.selected_contacts" )
                        i.dropdown.icon
                        div.default.text Select Contact
                        div.menu
                            div.item(
                                v-for="contact in contacts"
                                ":data-value"="contact.id" )
                                | {{contact.name}}
            div.sixteen.wide.column
                div.ui.container
                    div.ui.hidden.divider
                    div.ui.container.right.aligned
                        div.ui.button.secondary( @click="goBack()" ) Back
                        div.ui.button.primary(
                            type="submit"
                            @keydown.enter.prevent="previewArticle"
                            @click="previewArticle" )
                            | Förhandsgranska
</template>

<script lang="coffee">
    module.exports =
        name: 'ArticleForm'
        components:
            ImageDropzone: require './ImageDropzone.vue'
            UserCard: require './UserCard.vue'
        props: [ 'original', 'categories', 'contacts' ]
        data: ->
            ready: false
            settings:
                publish_interval: false
                bidding_interval: false
                form:
                    inline: true
                    on: 'blur'
                    onSuccess: =>
                        bus.$emit 'article_form_preview', @article
                    fields:
                        name:
                            identifier: 'name'
                            rules: [
                                type: 'empty'
                                prompt: 'Please enter article name'
                            ]
                        desc:
                            identifier: 'desc'
                            rules: [
                                type: 'empty'
                                prompt: 'Please select a role'
                            ]
                        price:
                            identifier: 'price'
                            rules: [
                                type: 'integer'
                                prompt: 'Please enter price'
                            ]
        computed:
            selectedContacts: ->
                @contacts.filter (contact) =>
                    for selected in @article.selected_contacts
                        if Number(contact.id) == Number(selected)
                            return true
                        return false
        methods:
            previewArticle: ->
                if @settings.publish_interval == false
                    @article.publish_interval = ''
                if @settings.bidding_interval == false
                    @article.bidding_interval = ''
                if !@ready
                    $('#article_form').form(@settings.form).form 'validate form'
                    @ready = true
                else
                    $('#article_form').form 'validate form'

            showRangePicker: (e) ->
                article = @article
                key = e.target.name
                range = e.target.value or ''
                bus.$emit('show_message',
                    title: 'Välj datum'
                    message: ''
                    start: range.split('|')[0]
                    end: range.split('|')[1]
                    type: 'calendar'
                    cb: ( start, end ) =>
                        article[key] = start.format('YYYY-MM-DD HH:mm:ss') + ' | ' + end.format('YYYY-MM-DD HH:mm:ss')
                )

            updateImageOrder: ->
                # Apply current order if any
                @article.images = @article.images.sort (a, b) => a.order-b.order
                # Then set it
                for image, index in @article.images
                    image.order = index

        created: ->
            # If a original is passed (Update-mode), fill the form
            if @original
                @article = @original
            # Check if using publish_interval
            if @article.publish_interval != ''
                @settings.publish_interval = true
            # Check if using bidding_interval
            if @article.bidding_interval != ''
                @settings.bidding_interval = true
            # Refresh Image ordering
            @updateImageOrder()
            # Listen for changes in Categories
            bus.$on 'categories_changed', (id, new_value) =>
                @article.selected_categories = new_value
            # Listen for changes in Contacts
            bus.$on 'contacts_changed', (id, new_value) =>
                @article.selected_contacts = new_value
            # Listen for changes in Images
            bus.$on 'image_added', (image) =>
                @article.images.push image
                @updateImageOrder()
            bus.$on 'image_removed', (image) =>
                for index, img of @article.images
                    if Number(img.id) == Number(image.id)
                        @article.images.splice index, 1
                @updateImageOrder()
            bus.$on 'image_reorder', =>
                @updateImageOrder()
        beforeDestroy: ->
            bus.$off 'categories_changed'
            bus.$off 'contacts_changed'
            bus.$off 'image_added'
            bus.$off 'image_removed'
</script>
