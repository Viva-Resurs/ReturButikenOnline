<template>
    <div class="container">

        <h2>Articles</h2>

        <div v-if="this.$root.loading">
            <loading></loading>
        </div>

        <div v-else>

            <div v-if="ithems.length > 0">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Name</td>
                            <td>Desc</td>
                            <td>Public</td>
                            <td>Sale</td>
                            <td>Bidding</td>
                            <td>Updated</td>
                            <td>Tools</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ithem in ithems">
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

    </div>
</template>

<script>
    export default {

        name: 'Articles',

        data: function(){
            return {
                ithems: [],
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
            }

        },

        created: function(){
            this.getArticles();
        }

    }
</script>
