<template lang="pug">
    div.ui.container.segment#articleForm

        div.ui.dividing.header Publicera

        form.ui.form(
            "v-on:submit.prevent"="attemptSave"
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
                div.field
                    date-interval(
                        v-show="settings.publish_interval"
                        interval="publish_interval_form"
                        ":date"="article.publish_interval"
                    )
                div.field
                    date-interval(
                        v-show="settings.bidding_interval"
                        interval="bidding_interval_form"
                        ":date"="article.bidding_interval"
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

            div.field
                div.two.fields
                    div.field
                        label Namn kontaktperson:
                        input( type="text" id="fullname" placeholder="Ditt namn" )
                    div.field
                        label Telefon kontaktperson:
                        input( type="text" id="phone" placeholder="Ditt nummer" )

                label E-post kontaktperson:
                input#email( type="email" placeholder="Din epost" )

            div.field
                button.ui.right.floated.button.primary(
                    type="submit"
                    @keydown.enter.prevent="attemptSave"
                ) Publicera
                button.ui.right.floated.button(
                    type="submit"
                ) Förhandsgranska

            br
            br
</template>

<script lang="coffee">
    DateInterval = require './DateInterval.vue'
    ImageDropzone = require './ImageDropzone.vue'

    module.exports =

        name: 'ArticleForm'

        components: { DateInterval, ImageDropzone },

        props: [ 'original' ],

        data: -> {
            article:
                name: ''
                desc: ''
                public: false
                publish_interval: ''
                bidding_interval: ''
                selected_categories: []
                selected_images: []
            categories:
                null
            settings:
                publish_interval: false
                bidding_interval: false
            myform: []
        }

        methods:

            attemptSave: ->

                # Todo: test
                if (this.settings.publish_interval == false)
                    this.article.publish_interval = '';
                if (this.settings.bidding_interval == false)
                    this.article.bidding_interval = '';

                bus.$emit( 'article_form_update', this.article );

            getCategoryList: ->
                this.$http.get('categories').then(
                    (response) =>
                        this.categories = response.data;

                    (response) =>
                        bus.$emit('error', response);
                        this.categories = []

                );

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

            # Listen for changes in DateInterval
            bus.$on('publish_interval_form_changed', (id,new_value) =>
                this.article.publish_interval = new_value;
            );
            bus.$on('bidding_interval_form_changed', (id,new_value) =>
                this.article.bidding_interval = new_value;
            );

            # Listen for changes in Categories
            bus.$on('categories_changed', (id,new_value) =>
                this.article.selected_categories = new_value;
            );

            # Get categories
            @getCategoryList()

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
            bus.$off('publish_interval_form_changed');
            bus.$off('bidding_interval_form_changed');

</script>
