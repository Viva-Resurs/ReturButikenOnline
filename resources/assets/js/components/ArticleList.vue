<template>
    <div class="panel panel-default">

        <div class="panel-heading">
            Articles
        </div>

        <div class="panel-body">

            <template v-if="this.$root.loading">
                <loading></loading>
            </template>

            <template v-else>

                <template v-if="ithems.length > 0">

                    <input v-model="search">
                    <hr>
                    <table class="table table-responsive table-condensed">
                        <thead>
                            <tr>
                                <th class="num"></th>
                                <th>Name <button class="btn btn-default btn-sm fa fa-btn fa-sort" @click="setOrder('name')"></button></th>
                                <th>Desc</th>
                                <th>Public</th>
                                <th>Sale</th>
                                <th>Bidding</th>
                                <th>Updated <button class="btn btn-default btn-sm fa fa-btn fa-sort" @click="setOrder('updated_at',1)"></th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ithem, index) in filterIthems">
                                <td class="num"><strong>{{(index+1)+offset}}. </strong></td>
                                <td>{{ithem.name}}</td>
                                <td class="desc">{{ithem.desc}}</td>

                                <td v-if="ithem.public == 1"><span class="fa fa-btn fa-check"></span></td>
                                <td v-else><span class="fa fa-btn fa-remove"></span></td>
                                <td><a class="btn btn-default btn-sm fa fa-btn fa-calendar" data-toggle="tooltip" v-bind:title="ithem.publish_interval"></a></td>
                                <td><a class="btn btn-default btn-sm fa fa-btn fa-calendar-check-o" data-toggle="tooltip" v-bind:title="ithem.bidding_interval"></a></td>
                                <td><span class="fa fa-btn fa-clock-o"></span> {{ithem.updated_at}}</td>
                                <td class="tools">
                                    <router-link :to="'/articles/'+ithem.id" class="btn btn-default btn-sm fa fa-btn fa-pencil"></router-link>
                                    <button class="btn btn-default btn-sm fa fa-btn fa-trash" @click="remove(ithem)"></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <pagination
                        :total="ithems.length"
                        :show-pagination="(search=='' && !limitOffBtn)"
                    >
                        <div slot="replacePagination">
                            <button v-if="limitOffBtn" class="btn btn-default searchresults_expander" @click="limitOff = true">
                                Visa alla resultat
                            </button>
                        </div>
                    </pagination>

                </template>

                <template v-else>
                    Empty
                </template>

            </template>

        </div>

    </div>
</template>

<script>
    import Filters from '../mixins/Filters.vue';
    import Pagination from './Pagination.vue';

    export default {

        name: 'ArticleList',

        mixins: [ Filters ],

        components: { Pagination },

        props: [ 'ithems' ],

        computed: {
            filterIthems(){
                return this.ithems
                    .filter(
                        (ithem) => (ithem.removed!==true)
                    )
                    .filter(
                        (ithem) => this.filterBy(ithem,this.search,this.targets)
                    )
                    .sort(
                        (a,b) => this.shallowSort(a[this.order],b[this.order],this)
                    )
                    .filter(
                        (ithem,index) => this.rangeFilter(ithem,index,this)
                    );
            }
        },

        data: function(){
            return {
                search: '',
                targets: ['name','updated_at'],

                order: 'name',
                desc: -1,

                limitOff: false,
                limitOffBtn: false,

                offset: 0,
                maxIthems: 10
            };
        },

        methods: {
            remove(ithem){
                bus.$emit('remove',ithem);
            }
        },

        watch: {
            // Reset show all results when editing search
            search: function(val, oldVal){
                this.offset = 0;
                this.limitOff = false;
            },
            maxIthems: function(val, oldVal){
                this.offset = 0;
            }
        },

        created: function(){

            // Listen for changes in data by components
            bus.$on('offset_changed', new_offset => this.offset = new_offset);
            bus.$on('limit_changed', new_limit => this.maxIthems = new_limit);
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });

        }
    };
</script>
<style>
    td {
        word-wrap: break-word;
        min-width: 80px;
        max-width: 160px;
        white-space:normal !important;
    }

    td.desc {
        max-width: 250px;
    }

    td.tools {
        max-width: 100px;
        min-width: 100px;
    }

    td.num, th.num {
        max-width: 40px;
        min-width: 40px;

    }

    td.num {
        background: #f5f8fa;
    }

</style>
