<template>
    <div class="ui container segment">

        <div class="ui dividing header">
            Arkiv
        </div>

            <template v-if="this.$root.loading">
                <loading></loading>
            </template>

            <template v-else>

                <div class="ui icon input">
                    <input v-focus v-model="search" class="prompt" placeholder="Type to search">
                    <i class="search icon"></i>
                </div>

                <div class="ui warning message" v-if="countItems==0">
                    <p>
                        {{ (items.length > 0) ? 'No results' : 'Empty' }}
                    </p>
                </div>

                <table class="ui compact celled table" v-if="countItems > 0">
                    <thead>
                        <tr>
                            <th class="center aligned collapsing">#</th>
                            <th @click="setSortBy('name');" class="link">
                                Name
                                <i :class="[headers.name, headers.name_icon]" ></i>
                            </th>
                            <th @click="setSortBy('updated_at');" class="link">
                                Updated
                                <i :class="[headers.updated_at, headers.updated_at_icon]"></i>
                            </th>

                            <th class="center aligned">Public</th>
                            <th class="center aligned">Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in filterItems">
                            <td class="center aligned warning collapsing"><strong>{{(index+1)+offset}}. </strong></td>
                            <td v-tooltip :data-html="displaySummary(item.desc)">
                                {{item.name}}
                            </td>

                            <td>
                                {{item.updated_at}}
                            </td>

                            <td class="center aligned">
                                <i :class="'ui icon ' + ((item.public==1) ? 'checkmark box' : 'square outline')"
                                    v-tooltip :data-html="((item.public==1) ? 'Publicerad för allmänheten' : 'Publicerad på kommunens intranät')"
                                ></i>
                            </td>

                            <td class="collapsing">
                                <div class="ui icon basic buttons">
                                    <button class="ui icon button hover-default"
                                        v-tooltip :data-html="displayInterval(item.publish_interval)"
                                        @click="openRangePicker" :id="item.id" :data-value="item.publish_interval" name="publish_interval"
                                        >
                                        <i :class="'ui icon dollar '+((activeInterval(item.publish_interval)) ? 'active-interval':'')"></i>
                                    </button>
                                    <button class="ui icon button hover-default"
                                        v-tooltip :data-html="displayInterval(item.bidding_interval)"
                                        @click="openRangePicker" :id="item.id" :data-value="item.bidding_interval" name="bidding_interval"
                                        >
                                        <i :class="'ui icon legal '+((activeInterval(item.bidding_interval)) ? 'active-interval':'')"></i>
                                    </button>
                                    <router-link
                                        :to="'/articles/'+item.id"
                                        class="ui icon button hover-primary"
                                        v-tooltip data-html="Edit"
                                        >
                                        <i class="ui icon pencil"></i>
                                    </router-link>

                                    <button class="ui icon button hover-danger"
                                        v-tooltip data-html="Remove"
                                        @click="remove(item)">
                                        <i class="ui icon trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">
                                <pagination
                                    :total="countItems"
                                    :show-pagination="(search=='' && !limitOffBtn)"
                                >
                                    <div slot="replacePagination">
                                        <button v-if="limitOffBtn" class="ui button searchresults_expander" @click="limitOff = true">
                                            Visa alla resultat
                                        </button>
                                    </div>
                                </pagination>
                            </th>
                        </tr>
                    </tfoot>
                </table>

            </template>

    </div>
</template>

<script lang="coffee">
    Filters = require '../mixins/Filters.vue';
    DateRangePicker = require '../mixins/DateRangePicker.vue';
    Pagination = require './Pagination.vue';

    module.exports = {

        name: 'ArticleList',

        mixins: [ Filters, DateRangePicker ]

        components: { Pagination }

        props: [ 'items' ]

        computed:
            filterItems: ->
                this.items
                    .filter(
                        (item) => (item.removed!=true)
                    )
                    .filter(
                        (item) => this.filterBy(item,this.search,this.targets)
                    )
                    .sort(
                        (a,b) => this.shallowSort(a[this.order],b[this.order],this)
                    )
                    .filter(
                        (item,index) => this.rangeFilter(item,index,this)
                    );

            countItems: ->
                this.items
                    .filter(
                        (item) => (item.removed!=true)
                    )
                    .filter(
                        (item) => this.filterBy(item,this.search,this.targets)
                    )
                    .length


        data: ->
            search: '',
            targets: ['name','updated_at'],

            order: 'name',
            desc: -1,

            limitOff: false,
            limitOffBtn: false,

            offset: 0,
            maxItems: 10,
            headers :
                name : "ui icon",
                name_icon : 'sort',
                updated_at : 'ui icon',
                updated_at_icon : 'sort',




        methods:
            displayInterval: (interval) ->
                if (interval.indexOf('|') < 0)
                    return 'Click to set interval'

                dates = interval.split('|')

                if (dates.length < 2)
                    return false

                return "<b>Start:</b><br>" + dates[0] + "<br><br>"+
                       "<b>End:</b><br>" + dates[1]

            activeInterval: (interval) ->
                if (interval=='' || typeof interval !='string' || interval.split('|')[1]=='')
                    return false;
                return moment().isBefore( interval.split('|')[1] )

            displaySummary: (info) ->
                formated = info.replace(/\n/g,'<br>')
                return formated;

            remove: (item) ->
                # Validation?
                bus.$emit('article_remove',item)

            openInterval: (e) ->
                $(e.target).closest('td').find('input').trigger('click')
                console.log( $(e.target).closest('td').find('input') )


            setSortBy: (headingTitle) ->

                # Set correct sort icon to the header (ascending, descending)
                selectedHeader = ''

                switch headingTitle
                    when 'name'
                        this.setOrder('name')
                        selectedHeader = if (this.desc == 1) then "sort alphabet ascending icon" else "sort alphabet descending icon"

                    when 'updated_at'
                        this.setOrder('updated_at',1)
                        selectedHeader = if (this.desc == 1) then "sort numeric ascending icon" else "sort numeric descending icon"


                this.headers[headingTitle+'_icon'] = selectedHeader

                # Change the other (not sorted by) icons to a generic sort icon
                for target in @targets
                    if (target != headingTitle)
                        @headers[target+'_icon'] = "sort"

        watch:
            # Reset show all results when editing search
            search: (val, oldVal) ->
                this.offset = 0
                this.limitOff = false

            maxItems: (val, oldVal) ->
                this.offset = 0



        created: ->
            bus.$on('publish_interval_changed', (id,new_value) =>
                for item in this.items
                    if (Number item.id == Number id)
                        item.publish_interval = new_value
                        bus.$emit('article_changed',item)

            );

            bus.$on('bidding_interval_changed', (id,new_value) =>
                for item in this.items
                    if (Number item.id == Number id)
                        item.bidding_interval = new_value
                        bus.$emit('article_changed',item)

            );

            # Listen for changes in data by components
            bus.$on('offset_changed', (new_offset) => this.offset = new_offset)
            bus.$on('limit_changed', (new_limit) => this.maxItems = new_limit)



        beforeDestroy: ->
            bus.$off('publish_interval_changed')
            bus.$off('bidding_interval_changed')
            bus.$off('offset_changed')
            bus.$off('limit_changed')

    }
</script>
