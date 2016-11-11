<template>
    <div class="ui container segment" id="articleForm">

        <div class="ui dividing header">
            Publicera
        </div>

        <form class="ui form" v-on:submit.prevent="attemptCreate" role="form" name="myform">

                <div class="fields">
                    <div class="twelve wide field">
                        <label for="name">Varunamn:</label>
                        <input id="name"
                            type="text"
                            v-model="article.name"
                            placeholder="Varunamn"
                        >
                    </div>
                    <div class="four wide field" style="white-space: nowrap;">
                        <label class="control-label" for="category">Välj varukategori:</label>
                        <div v-if="categories" class="ui fluid multiple selection dropdown" name="categories" id="category"
                            v-dropdown :data-selected="article.selected_categories">
                            <i class="dropdown icon"></i>
                            <div class="default text">Select Category</div>
                            <div class="menu">
                                <div v-for="category in categories" class="item" :data-value="category.id">
                                    {{category.name}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label for="desc">Beskrivning av varan:</label>
                    <textarea class="form-control" rows="4"
                        id="desc"
                        v-model="article.desc"
                        placeholder="Ge din beskrivning här"
                    ></textarea>
                </div>

                <div class="field">
                    <div class="inline fields">

                        <div class="field">
                            <div class="ui checkbox" v-checkbox>
                                <input type="checkbox" tabindex="0" value="0" class="hidden" v-model="settings.publish_interval">
                                <label>Publicera inom datumintervall</label>
                            </div>
                        </div>

                        <div class="field">
                            <div class="ui checkbox" v-checkbox>
                                <input type="checkbox" tabindex="1" value="1" class="hidden" v-model="settings.bidding_interval">
                                <label>Aktivera budgivning</label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="field" v-show="settings.publish_interval">
                    <date-interval interval="publish_interval_form" :date="article.publish_interval"></date-interval>
                </div>

                <div class="field" v-show="settings.bidding_interval">
                    <date-interval interval="bidding_interval_form" :date="article.bidding_interval"></date-interval>
                </div>

                <div class="inline fields">

                    <div class="field">
                      <div class="ui radio checkbox" v-checkbox>
                        <input type="radio" name="public" tabindex="0" class="hidden" v-model="article.public" value="0">
                        <label>Publicera på kommunens Intranät</label>
                      </div>
                  </div>
                  <div class="field">
                    <div class="ui radio checkbox" v-checkbox>
                      <input type="radio" name="public" tabindex="1" class="hidden" v-model="article.public" value="1">
                      <label>Publicera för allmänheten</label>
                    </div>
                </div>

              </div>

               <div class="field">
                   <div class="two fields">
                       <div class="field">
                           <label for="fullname">Namn kontaktperson:</label>
                           <input type="text" id="fullname" placeholder="Ditt namn">
                       </div>
                       <div class="field">
                           <label for="phone">Telefon kontaktperson:</label>
                           <input type="text" id="phone" placeholder="Ditt nummer">
                       </div>
                    </div>
                    <div class="field">
                       <label class="control-label" for="email">E-post kontaktperson:</label>
                       <input type="email" class="form-control" id="email" placeholder="Din epost">
                   </div>
               </div>

            <div class="field">
                <button type="submit" class="ui right floated button primary " @keydown.enter.prevent="attemptCreate">
                    Publicera
                </button>
                <button type="submit" class="ui right floated button">Förhandsgranska</button>
            </div>
            <br />
        </form>
        <br />

    </div>
</template>

<script lang="coffee">
    DateInterval = require './DateInterval.vue'

    module.exports =

        name: 'ArticleForm'

        components: { DateInterval },

        props: [ 'original' ],

        data: -> {
            article:
                name: ''
                desc: ''
                public: false
                publish_interval: ''
                bidding_interval: ''
                selected_categories: []
            categories:
                null
            settings:
                publish_interval: false
                bidding_interval: false
            myform: []
        }

        methods:

            attemptCreate: ->

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

        ready: ->
            @initialSelected()

        beforeDestroy: ->
            bus.$off('publish_interval_form_changed');
            bus.$off('bidding_interval_form_changed');

</script>
