<template lang="pug">
    div.ui.padded.container.segment.attached#articleForm

        div.ui.dividing.header Publicera

        form.ui.form(
            "v-on:submit.prevent"="previewArticle"
            role="form"
        )

            div.fields
                div.twelve.wide.field
                    label Varunamn:
                    input#name(
                        type="text"
                        "v-model"="article.name"
                        placeholder="Varunamn"
                    )
                div.four.wide.field
                    label Välj varukategori:
                    div.ui.fluid.multiple.selection.dropdown#category(
                        v-if="categories"
                        name="categories"
                        v-dropdown=""
                        ":data-selected"="article.selected_categories"
                    )
                        i.dropdown.icon
                        div.default.text Select Category
                        div.menu
                            div.item(
                                v-for="category in categories"
                                ":data-value"="category.id"
                            ) {{category.name}}

            div.field
                label Beskrivning av varan:
                textarea#desc(
                    rows="4"
                    v-model="article.desc"
                    placeholder="Ge din beskrivning här"
                )

            div.field
                label Pris:
                input#price(
                    type="number"
                    v-model="article.price"
                    placeholder="Ange ditt pris här"
                )

            div.field
                label Bilder:
                image-dropzone( ":images"="article.selected_images" )

            div.two.fields
                div.field
                    div.ui.checkbox( v-checkbox="" )
                        input.hidden(
                            type="checkbox"
                            tabindex="0" value="0"
                            v-model="settings.publish_interval"
                        )
                        label Publicera inom datumintervall
                div.field
                    div.ui.checkbox( v-checkbox="" )
                        input.hidden(
                            type="checkbox"
                            tabindex="1" value="1"
                            v-model="settings.bidding_interval"
                        )
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
                            v-model="article.publish_interval"
                        )
                div.field( v-show="settings.bidding_interval" )
                    h4.ui.sub.header bidding_interval
                    div.ui.input.left.icon
                        i.calendar.icon
                        input(
                            type="text"
                            name="bidding_interval"
                            placeholder="????-??-??"
                            @click="showRangePicker"
                            v-model="article.bidding_interval"
                        )

            div.two.fields
                div.field
                    div.ui.radio.checkbox( v-checkbox="" )
                        input.hidden(
                            type="radio"
                            name="public"
                            tabindex="0" value="0"
                            v-model="article.public"
                        )
                        label Publicera på kommunens Intranät
                div.field
                    div.ui.radio.checkbox( v-checkbox="" )
                        input.hidden(
                            type="radio"
                            name="public"
                            tabindex="1" value="1"
                            v-model="article.public"
                        )
                        label Publicera för allmänheten

            div.ui.divider

            div.two.fields( v-if="contacts" )
                div.field
                    label Kontakt:
                    div.field( v-if="article.selected_contacts")
                        div.ui.fluid.card( v-for="contact in selectedContacts" )
                            div.content
                                div.header {{ contact.fullname }}
                                div.description
                                    p Telefon:&nbsp;
                                        b {{ contact.phone }}
                                    p E-post:&nbsp;
                                        b {{ contact.email }}
                div.field( v-if="article.selected_contacts && contacts.length>1" )
                    label Välj kontakt:
                    div.ui.fluid.selection.dropdown#contact(
                        v-if="contacts"
                        name="contacts"
                        v-dropdown=""
                        ":data-selected"="article.selected_contacts"
                    )
                        i.dropdown.icon
                        div.default.text Select Contact
                        div.menu
                            div.item(
                                v-for="contact in contacts"
                                ":data-value"="contact.id"
                            ) {{contact.name}}
            div.field
                button.ui.right.floated.button(
                    type="submit"
                    @keydown.enter.prevent="previewArticle"
                ) Förhandsgranska

            br
            br
</template>

<script lang="coffee">
    ImageDropzone = require './ImageDropzone.vue'

    module.exports =

        name: 'ArticleForm'

        components: { ImageDropzone },

        props: [ 'original', 'categories', 'contacts' ],

        data: -> {
            settings:
                publish_interval: false
                bidding_interval: false
            myform: []
        }

        computed:
            selectedContacts: () ->
                @contacts
                    .filter(
                        (contact) =>
                            for selected in @article.selected_contacts
                                if (Number(contact.id) == Number(selected))
                                    return true
                                return false
                    )

        methods:

            previewArticle: ->

                if (this.settings.publish_interval == false)
                    this.article.publish_interval = '';
                if (this.settings.bidding_interval == false)
                    this.article.bidding_interval = '';

                bus.$emit( 'article_form_preview', this.article );

            showRangePicker: (e) ->
                article = @article
                key = e.target.name
                range = e.target.value or ''

                bus.$emit('show_message', {
                    title:'Välj datum',
                    message:'',
                    start: range.split('|')[0],
                    end: range.split('|')[1]
                    type:'calendar',
                    cb: ( start, end ) =>
                        article[key] = start.format('YYYY-MM-DD HH:mm:ss') + ' | ' + end.format('YYYY-MM-DD HH:mm:ss')
                });

        created: ->

            # If a original is passed (Update-mode), fill the form
            if (this.original)
                this.article = this.original;

            # Check if using publish_interval
            if (this.article.publish_interval!='')
                this.settings.publish_interval = true;

            # Check if using bidding_interval
            if (this.article.bidding_interval!='')
                this.settings.bidding_interval = true;

            # Listen for changes in Categories
            bus.$on('categories_changed', (id,new_value) =>
                this.article.selected_categories = new_value;
            );

            # Listen for changes in Contacts
            bus.$on('contacts_changed', (id,new_value) =>
                this.article.selected_contacts = new_value;
            );

            # Images
            bus.$on('image_added', (image) =>
                console.log image
                @article.selected_images.push(image)
            )
            bus.$on('image_removed', (image) =>
                console.log image
                for index, img of @article.selected_images
                    if (Number img.id == Number image.id)
                        @article.selected_images.splice(index,1)
            )

        beforeDestroy: ->
            bus.$off('contacts_changed');
            bus.$off('image_added');
            bus.$off('image_removed');

</script>
