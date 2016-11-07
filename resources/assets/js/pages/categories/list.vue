<template>
<div class="ui container segment">

    <div class="ui dividing header">
        Kategorier
    </div>

        <div v-if="this.$root.loading">
            <loading></loading>
        </div>

        <div v-else>

                <table class="ui compact celled table">
                    <thead class="thead-default">
                        <tr>
                            <th>Name <a @click="setOrder('name')">S</a></th>
                            <th>Updated <a @click="setOrder('updated_at',1)">S</a></th>
                            <th class="center aligned">Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="ithems.length > 0" v-for="(ithem, index) in filterIthems">
                            <td v-show="!ithem.edit">{{ithem.name}}</td>
                            <td v-show="ithem.edit">
                                <div class="ui input">
                                    <input v-model="ithem.name">
                                </div>
                            </td>
                            <td class="collapsing">{{ithem.updated_at}}</td>
                            <td v-if="!ithem.edit" class="collapsing">
                                <div class="ui icon basic buttons">
                                <button class="ui icon button hover-primary" @click="editIthem(ithem)"
                                    v-tooltip data-html="Edit">
                                    <i class="ui icon pencil"></i>
                                </button>
                                <button class="ui icon button hover-danger" @click="attemptRemove(ithem)"
                                    v-tooltip data-html="Remove">
                                <i class="ui icon trash"></i>
                                </button>
                                </div>
                            </td>
                            <td v-if="ithem.edit" class="collapsing">
                                <a role="button" @click="revertIthem(ithem)">Cancel</a>
                                <a role="button" @click="attemptUpdate(ithem)">Save</a>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <button class="ui basic icon button" @click="newIthem">
                                    <i class="ui icon plus"></i>
                                </button>

                            </td>
                        </tr>
                    </tbody>
                </table>



        </div>

</div>
</template>

<script>
import Filters from '../../mixins/Filters.vue'

export default {

    name: 'List',

    mixins: [Filters],

    computed: {
        filterIthems() {
            return this.ithems
                .filter(
                    (ithem) => (ithem.removed != true)
                )
                .sort(
                    (a, b) => this.shallowSort(a[this.order], b[this.order], this)

                )
        }
    },

    data: function() {
        return {
            ithems: [],

            order: 'originalName',
            desc: -1
        }
    },

    methods: {

        newIthem() {
            var category = {
                name: 'namnlös kategori'
            };

            this.$http.post('categories', category).then(
                (response) => {
                    console.log('ok');
                    category = response.data;
                    this.ithems.push(category);
                    this.editIthem(category);
                },
                (response) => bus.$emit('error', response)
            );
        },

        editIthem(ithem) {
            ithem.edit = true;
            this.ithems.reverse();
        },

        revertIthem(ithem) {
            ithem.name = ithem.originalName;
            ithem.edit = false;
            this.ithems.reverse();
        },

        attemptUpdate(category) {

            this.$http.put('categories/' + category.id, category).then(
                (response) => {
                    console.log('ok');
                    category.edit = false;
                    category.originalName = category.name;
                    this.ithems.reverse();
                },
                (response) => bus.$emit('error', response)
            );

        },

        attemptRemove(category) {
            // Are you sure?
            // ... yes
            this.removeCategory(category);
        },

        removeCategory(category) {
            this.$http.delete('categories/' + category.id).then(
                (response) => {
                    bus.$emit('success', 'removed_category');
                    category.removed = true;
                    this.ithems.reverse();
                },
                (response) => bus.$emit('error', response)
            );
        },

        getCategories() {

            this.$root.loading = true;

            this.$http.get('categories').then(
                (response) => {
                    this.ithems = response.data;

                    // Fill in all ithems originalName
                    for (var i = 0; i < this.ithems.length; i++)
                        this.ithems[i].originalName = this.ithems[i].name;

                    this.$root.loading = false;
                },
                (response) => {
                    bus.$emit('error', response);
                    this.$root.loading = false;
                }
            );
        }

    },

    created: function() {
        this.getCategories();
    }
}
</script>
