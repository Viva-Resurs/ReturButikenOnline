<template>
    <div class="panel panel-default">

        <div class="panel-heading">
            Articles
        </div>

        <div class="panel-body">

            <div v-if="this.$root.loading">
                <loading></loading>
            </div>

            <div v-else>

                <div v-if="ithems.length > 0">

                    <input v-model="search">

                    <table class="table">
                        <thead>
                            <tr>
                                <td></td>
                                <td>Name <a @click="setOrder('name')">S</a></td>
                                <td>Desc</td>
                                <td>Public</td>
                                <td>Sale</td>
                                <td>Bidding</td>
                                <td>Updated <a @click="setOrder('updated_at',1)">S</a></td>
                                <td>Tools</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ithem, index) in filterIthems">
                                <td>{{(index+1)+offset}}.</td>
                                <td>{{ithem.name}}</td>
                                <td>{{ithem.desc}}</td>
                                <td>{{ithem.public}}</td>
                                <td>{{ithem.publish_interval}}</td>
                                <td>{{ithem.bidding_interval}}</td>
                                <td>{{ithem.updated_at}}</td>
                                <td>X E</td>
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

                </div>

                <div v-else>
                    Empty
                </div>

            </div>
        </div>

    </div>
</template>

<script>
    import Filters from '../../mixins/Filters.vue'
    import Pagination from '../../components/Pagination.vue'

    export default {

        name: 'List',

        mixins: [ Filters ],

        components: { Pagination },

        computed: {
            filterIthems(){
                return this.ithems
                    .filter(
                        (ithem) => this.filterBy(ithem,this.search,this.targets)
                    )
                    .sort(
                        (a,b) => this.shallowSort(a[this.order],b[this.order],this)
                        
                    )
                    //(a, b) => a[this.order] > b[this.order] ? 1*this.desc : -1*this.desc
                    .filter(
                        (ithem,index) => this.rangeFilter(ithem,index,this)
                    )
                    //.splice(this.pagination_position, this.pagination_results)
            },
            pagination_position(){
                return (this.search) ? 0 : this.offset;
            },
            pagination_results(){
                return (this.limitOff) ? this.ithems.length : this.maxIthems;
            }
        },

        data: function(){
            return {
                ithems: [],

                search: '',
                targets: ['name','updated_at'],

                order: 'name',
                desc: -1,

                limitOff: false,
                limitOffBtn: false,

                offset: 0,
                maxIthems: 10
            }
        },

        methods: {

            getArticles(){

                this.$root.loading = true;

                this.$http.get('articles').then(
                    (response) => {
                        this.ithems = response.data;
                        this.$root.loading = false;
                    },
                    (response) => {
                        bus.$emit('error',response);
                        this.$root.loading = false;
                    }
                );
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
            this.getArticles();

            // Listen for changes in data by components
            bus.$on('offset_changed', new_offset => this.offset = new_offset);
            bus.$on('limit_changed', new_limit => this.maxIthems = new_limit);

        }
    }
</script>
