<template>
    <div class="ui container segment">
        <div class="ui dividing header">
            {{header}}
        </div>
        <template v-if="this.$root.loading">
            <loading></loading>
        </template>
        <template v-else>
            <div class="ui icon input">
                <input v-focus v-model="search" class="prompt" placeholder="Type to search">
                <i class="search icon"></i>
            </div>
            <div class="ui padded grid">
                <div class="row" v-if="countItems==0">
                    <div class="ui warning message" >
                        <p>{{ (items.length > 0) ? 'No results' : 'Empty' }}</p>
                    </div>
                </div>
                <div class="row computer only" v-if="columns && card">
                    <table class="ui compact celled table" v-if="countItems > 0">
                        <thead>
                            <tr>
                                <th class="center aligned collapsing">#</th>
                                <th v-for="column in columns"
                                    :class="column.class"
                                    @click="(column.sort) ? setSortBy(column.key) : false">
                                    {{column.label}} <i v-if="column.sort" :class="[headers[column.key], headers[column.key+'_icon']]" ></i>
                                </th>
                                <th class="center aligned">Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in filterItems">
                                <td class="center aligned warning collapsing"><strong>{{(index+1)+offset}}. </strong></td>

                                <td v-for="column in columns"
                                    :class="column.class"
                                    v-tooltip :data-html="formatTooltip(item[column.tooltip])"
                                    >
                                    <span v-if="column.type=='string' || column.type=='number' || column.type==''">
                                        {{item[column.key]}}
                                    </span>
                                    <div v-if="column.type=='checkbox'" class="center aligned">
                                        <i :class="'ui icon ' + ((item[column.key]==1) ? 'checkmark box' : 'square outline')"
                                            v-tooltip :data-html="((item[column.key]==1) ? column.checkbox_true : column.checkbox_false)"
                                        ></i>
                                    </div>
                                </td>
                                <td class="collapsing">
                                    <div class="ui icon basic buttons">
                                        <component v-for="tool in toolsRow" :is="tool" :item="item" >
                                        </component>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="mobile tablet only row">
                    <div class="ui fluid card" v-for="(item, index) in filterItems">
                        <div class="content">
                            <div class="header">{{item[card.header.label]}}</div>
                            <div class="meta">
                                <div v-for="meta in card.meta" :class="meta.class">
                                    {{meta.title}}: {{item[meta.label]}}
                                </div>
                            </div>
                            <div class="description">
                                {{item[card.description.label]}}
                            </div>
                        </div>
                        <div class="extra content">
                            <div class="meta left floated">
                                <div v-for="extra in card.extra" :class="extra.class">
                                    <span v-if="extra.type=='boolean'">
                                        {{ (item[extra.label]) ? extra.true : extra.false }}
                                    </span>
                                </div>
                            </div>
                            <component :is="tools" :item="item" class="meta right floated">
                                <div slot="extraTools">
                                    <component :is="extraTools" :item="item">
                                    </component>
                                </div>
                            </component>
                        </div>
                    </div>
                </div> -->
                <div class="row" v-if="!card">
                    <table class="ui compact unstackable celled table">
                        <thead>
                            <tr>
                                <th class="center aligned collapsing">#</th>
                                <th v-for="column in columns"
                                    :class="column.class"
                                    @click="(column.sort) ? setSortBy(column.key) : false">
                                    {{column.label}} <i v-if="column.sort" :class="[headers[column.key], headers[column.key+'_icon']]" ></i>
                                </th>
                                <th class="center aligned">Tools</th>
                            </tr>
                        </thead>
                        <tbody v-item>
                            <tr v-for="(item, index) in filterItems" :id="item.id">
                                <td class="center aligned warning collapsing"><strong>{{(index+1)+offset}}. </strong></td>
                                <td v-for="column in columns"
                                    :class="column.class"
                                    v-tooltip :data-html="formatTooltip(item[column.tooltip])"
                                    >
                                    <div v-if="item.edit && column.type=='string'" class="ui input fluid">
                                        <input v-model="item[column.key+'_new']" :placeholder="'Type ' + column.label"
                                        @keyup.enter="attemptUpdate(item)"
                                        v-focus>
                                    </div>
                                    <div v-else>
                                        {{item[column.key]}}
                                    </div>
                                </td>
                                <td class="collapsing">
                                    <div class="ui icon basic buttons">
                                        <component v-for="tool in toolsRow" :is="tool" :item="item" >
                                        </component>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="(item, index) in itemsNew">
                                <td class="center aligned warning collapsing"></td>
                                <td v-for="column in columns"
                                    :class="column.class"
                                    v-tooltip :data-html="formatTooltip(item[column.tooltip])"
                                    >
                                    <div v-if="column.type=='string'" class="ui input fluid">
                                        <input v-model="item[column.key]" :placeholder="'Type ' + column.label"
                                        @keyup.enter="attemptUpdate(item)"
                                        v-focus>
                                    </div>
                                </td>
                                <td class="collapsing">
                                    <div class="ui icon basic buttons">
                                        <component v-for="tool in toolsRow" :is="tool" :item="item" >
                                        </component>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td v-for="c in columns"></td>
                                <td>
                                    <div class="ui icon basic buttons">
                                        <component v-for="tool in toolsBottom" :is="tool">
                                        </component>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination
                    :total="countItems"
                    :show-pagination="(search=='' && !limitOffBtn)"
                    class="row"
                >
                    <div slot="replacePagination">
                        <button v-if="limitOffBtn" class="ui floated right button searchresults_expander" @click="limitOff = true">
                            Visa alla resultat
                        </button>
                    </div>
                </pagination>
            </div>
        </template>
    </div>
</template>

<script lang="coffee">
    Filters = require '../mixins/Filters.vue'
    Pagination = require './Pagination.vue';

    module.exports = {
        name: 'ItemGrid'
        props: [ 'header', 'columns', 'card', 'items', 'itemsNew', 'toolsRow', 'toolsBottom' ]
        mixins: [ Filters ]
        components: { Pagination }
        data: ->
            search: ''

            limitOff: false
            limitOffBtn: false

            order: ''
            desc: -1
            headers :
                name : "ui icon"
                name_icon : 'sort'
                updated_at : 'ui icon'
                updated_at_icon : 'sort'

            offset: 0
            maxItems: 10

        computed:
            filterItems: ->
                this.items
                    .filter(
                        (item) => (item.removed!=true)
                    )
                    .filter(
                        (item) => @filterBy(item,@search,this.columns)
                    )
                    .sort(
                        (a,b) => @shallowSort(a[@order],b[@order],this)
                    )
                    .filter(
                        (item,index) => @rangeFilter(item,index,this)
                    );
            countItems: ->
                this.items
                    .filter(
                        (item) => (item.removed!=true)
                    )
                    .filter(
                        (item) => @filterBy(item,@search,@columns)
                    )
                    .length

        watch:
            # Reset show all results when editing search
            search: (val, oldVal) ->
                this.offset = 0
                this.limitOff = false
            # Reset offset when changing maxItems
            maxItems: (val, oldVal) ->
                this.offset = 0

        methods:
            formatTooltip: (info) ->
                if (!info)
                    return ''
                formated = info.replace(/\n/g,'<br>')
                return formated;

            setSortBy: (headingTitle) ->
                # Set correct sort icon to the header (ascending, descending)
                selectedHeader = ''

                for key, column of @columns
                    if (headingTitle=='' && column.default_sort)
                        this.setOrder(column.label,column.desc)
                        selectedHeader = if (this.desc == 1) then "sort numeric ascending icon" else "sort numeric descending icon"
                    if (column.label == headingTitle)
                        if (column.type == 'string')
                            this.setOrder(column.label,column.desc)
                            selectedHeader = if (this.desc == 1) then "sort alphabet ascending icon" else "sort alphabet descending icon"
                        if (column.type == 'number')
                            this.setOrder(column.label,column.desc)
                            selectedHeader = if (this.desc == 1) then "sort numeric ascending icon" else "sort numeric descending icon"

                this.headers[headingTitle+'_icon'] = selectedHeader

                # Change the other (not sorted by) icons to a generic sort icon
                for key, column of @columns
                    if (column.label != headingTitle)
                        @headers[column.label+'_icon'] = "sort"

        created: ->
            @setSortBy()
            # Listen for changes in data by components
            bus.$on('offset_changed', (new_offset) => this.offset = new_offset)
            bus.$on('limit_changed', (new_limit) => this.maxItems = new_limit)

        beforeDestroy: ->
            bus.$off('offset_changed')
            bus.$off('limit_changed')
    }
</script>
