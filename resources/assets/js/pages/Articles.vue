<template>
    <div class="container">

        <h2>Articles</h2>

        <div v-if="this.$root.loading">
            <loading></loading>
        </div>

        <div v-else>

            <div v-if="ithems.length > 0">

                <input v-model="search">

                <table class="table">
                    <thead>
                        <tr>
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
                        <tr v-for="ithem in filterIthems">
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
            </div>

            <div v-else>
                Empty
            </div>

        </div>
        <router-link to="/articles/create" class="btn btn-primary">Create Article</router-link>
        <button class="btn btn-primary" @click="seedArticles">SEED</button>

    </div>
</template>

<script>
    import Filters from '../mixins/Filters.vue'

    export default {

        name: 'Articles',

        mixins: [ Filters ],

        computed: {
            filterIthems(){
                return this.ithems
                    .filter(
                        (ithem) => this.filterBy(ithem,this.search,this.targets)
                    )
                    .sort(
                        (a, b) => a[this.order] > b[this.order] ? 1*this.desc : -1*this.desc
                    )
                    .slice(this.offset, this.maxIthems)
            }
        },

        data: function(){
            return {
                ithems: [],

                search: '',
                targets: ['name','updated_at'],

                order: 'name',
                desc: -1,

                offset: 0,
                maxIthems: 5
            }
        },

        methods: {

            getArticles(){

                this.$root.loading = true;

                this.$http.get('api/articles').then(
                    (response) => {
                        this.ithems = response.data;
                        this.$root.loading = false;
                    },
                    (response) => {
                        console.error(response.error);
                        this.$root.loading = false;
                    }
                );
            },

            seedArticles(){

                this.$root.loading = true;

                this.$http.post('api/articles',{name:'test'}).then(
                    (response) => {
                        this.ithems.push(response.data);
                        this.$root.loading = false;
                    },
                    (response) => {
                        console.error(response.error);
                        this.$root.loading = false;
                    }
                );
            }

        },

        created: function(){
            this.getArticles();
        }

    }
</script>
