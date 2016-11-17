<template>
    <div class="ui container segment">

        <div class="ui dividing header">
            Kategorier
        </div>

        <div v-if="this.$root.loading">
            <loading></loading>
        </div>

        <div v-else>

            <table class="ui compact unstackable celled table">
                <thead class="thead-default">
                    <tr>
                        <th @click="setSortBy('name');" class="link">
                            Name
                            <i :class="[headers.name, headers.name_icon]" ></i>
                        </th>
                        <th @click="setSortBy('updated_at');" class="link">
                            Updated
                            <i :class="[headers.updated_at, headers.updated_at_icon]"></i>
                        </th>
                        <th class="center aligned">Tools</th>
                    </tr>
                </thead>
                <tbody v-item id="category_content">
                    <tr v-if="items.length > 0" v-for="(item, index) in filteritems" :id="item.id">
                        <td v-show="!item.edit" @click="edititem(item)">{{item.name}}</td>
                        <td v-if="item.edit">
                            <div class="ui input fluid">
                                <input v-model="item.new_name" placeholder="Type category name"
                                @keyup.enter="attemptUpdate(item)"
                                v-focus>
                            </div>

                        </td>
                        <td class="collapsing">{{item.updated_at}}</td>
                        <td v-show="!item.edit" class="collapsing">
                            <div class="ui icon basic buttons">
                                <button class="ui icon button hover-primary" @click="edititem(item)"
                                    v-tooltip data-html="Edit">
                                    <i class="ui icon pencil"></i>
                                </button>
                                <button class="ui icon button hover-danger" @click="attemptRemove(item)"
                                    v-tooltip data-html="Remove">
                                <i class="ui icon trash"></i>
                                </button>
                            </div>
                        </td>
                        <td v-show="item.edit" class="collapsing">
                            <div class="ui icon basic buttons">
                                <button class="ui icon button hover-primary" @click="attemptUpdate(item)"
                                    v-tooltip data-html="Save">
                                    <i class="ui icon save"></i>
                                </button>
                                <button class="ui icon button" @click="revertitem(item)"
                                    v-tooltip data-html="Undo">
                                    <i class="ui icon undo"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ui input" v-if="adding">
                                <input v-model="new_category.name" placeholder="Type category name"
                                @keyup.enter="attemptCreate()"
                                v-focus>
                            </div>
                        </td>
                        <td></td>
                        <td class="collapsing">
                            <div class="ui icon basic buttons" v-show="adding">
                                <button class="ui icon button hover-primary" @click="attemptCreate()"
                                    v-tooltip data-html="Save">
                                    <i class="ui icon save"></i>
                                </button>
                                <button class="ui icon button" @click="adding = false"
                                    v-tooltip data-html="Undo">
                                    <i class="ui icon undo"></i>
                                </button>
                            </div>
                            <button v-if="!adding" class="ui basic icon button" @click="adding = true"
                                v-tooltip data-html="Add">
                                <i class="ui icon plus"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>
</template>

<script lang="coffee">
Filters = require '../../mixins/Filters.vue';

module.exports = {

    name: 'List'

    mixins: [Filters]

    computed:
        filteritems: ->
            this.items
                .filter(
                    (item) => (item.removed != true)
                )
                .sort(
                    (a, b) => this.shallowSort(a[this.order], b[this.order], this)
                )

    data: ->
        items: []
        targets: ['name','updated_at']
        order: 'originalName'
        desc: -1
        headers :
            name : "ui icon"
            name_icon : 'sort'
            updated_at : 'ui icon'
            updated_at_icon : 'sort'
        adding: false
        new_category:
            name: ''

    methods:

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

        attemptCreate: () ->
            this.$http.post('categories', @new_category).then(
                (response) =>
                    console.log('ok')
                    category = response.data
                    this.items.push(category)
                    @new_category.name = ''
                    @adding = false
                    this.$nextTick ->
                        $('#category_content').trigger('updated',category.id)

                (response) => bus.$emit('error', response)
            );


        edititem: (item) ->
            item.new_name = item.name
            item.edit = true;
            this.updateList(this.items)

        revertitem: (item) ->
            item.edit = false;
            this.updateList(this.items)

        attemptUpdate: (category) ->
            category.edit = false;
            category.name = category.new_name.trim()

            this.$http.put('categories/' + category.id, category).then(
                (response) =>
                    category.updated_at = response.data.updated_at
                    this.updateList(this.items)
                    this.$nextTick ->
                        $('#category_content').trigger('updated',category.id)

                (response) => bus.$emit('error', response)
            );

        attemptRemove: (category) ->
            # Are you sure?
            # ... yes
            this.removeCategory(category);

        removeCategory: (category) ->
            this.$http.delete('categories/' + category.id).then(
                (response) =>
                    bus.$emit('success', 'removed_category');
                    $('#category_content').trigger('removed',category.id, ->
                        category.removed = true;
                        this.updateList(this.items)
                    )
                (response) => bus.$emit('error', response)
            );

        getCategories: () ->

            this.$root.loading = true;

            this.$http.get('categories').then(
                (response) =>
                    this.items = response.data;

                    # Fill in all items originalName
                    for item in @items
                        item.originalName = item.name

                    this.$root.loading = false;

                (response) =>
                    bus.$emit('error', response);
                    this.$root.loading = false;

            );

    created: ->
        this.getCategories();

}
</script>
