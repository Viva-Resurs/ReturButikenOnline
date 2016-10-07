<template>
    <div id="articleForm">

        <div class="panel-heading">
            Skapa artikel
        </div>

        <form class="form-vertical" v-on:submit.prevent="attemptCreate" role="form" name="myform">

            <div class="panel-body">

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-10">
                            <label class="control-label" for="name">Varunamn:</label>
                            <input class="form-control"
                                id="name"
                                type="text"
                                v-model="article.name"
                                placeholder="Varunamn"
                            >
                        </div>
                        <div class="col-xs-2" style="white-space: nowrap;">
                            <div class="pull-right">
                                <label class="control-label" for="category">Välj varukategori:</label>
                                <div class="dropdown text-right">
                                    <button class="btn btn-default dropdown-toggle"
                                        id="category"
                                        type="button"
                                        data-toggle="dropdown"
                                    >
                                        Kategori <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="category">
                                        <li role="presentation">
                                            <a role="menuitem" href="#">Category item</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="control-label" for="desc">Beskrivning av varan:</label>
                            <textarea class="form-control" rows="4"
                                id="desc"
                                v-model="article.desc"
                                placeholder="Ge din beskrivning här"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="checkbox-inline">
                                <input type="checkbox" value="" v-model="article.publish_interval">
                                Publicera inom datumintervall
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="" v-model="article.bidding_interval">
                                Aktivera budgivning
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                      <dateinterval date_pub1.sync="date_start" date_pub2.sync="date_end"></dateinterval>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="radio-inline">
                                <input type="radio" name="public" v-model="article.public" value="on">
                                Publicera på kommunens Intranät
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="public" v-model="article.public" value="off"> Publicera för allmänheten
                            </label>
                        </div>
                    </div>
               </div>

               <div class="form-group">
                   <div class="row">
                       <div class="col-xs-6">
                           <label class="control-label" for="fullname">Namn kontaktperson:</label>
                           <input type="text" class="form-control" id="fullname" placeholder="Ditt namn">
                       </div>
                       <div class="col-xs-6">
                           <label class="control-label" for="phone">Telefon kontaktperson:</label>
                           <input type="text" class="form-control" id="phone" placeholder="Ditt nummer">
                       </div>
                    </div>
                    <div class="row">
                       <div class="col-xs-12">
                           <label class="control-label" for="email">E-post kontaktperson:</label>
                           <input type="email" class="form-control" id="email" placeholder="Din epost">
                       </div>
                   </div>
               </div>

            </div>

            <div class="panel-footer text-right">
                <button type="submit" class="btn btn-default">Förhandsgranska</button>
                <button type="submit" class="btn btn-primary" @keydown.enter.prevent="attemptCreate">
                    Publicera
                </button>
            </div>

        </form>

    </div>
</template>

<style>
  .panel-body{
    margin: 10px;
  }
</style>

<script>
Vue.component('dateinterval', require('./../../components/DateInterval.vue'));

export default {
    name: 'Create',
    data() {
        return {
            article: {
            },
            myform: []
            ,
            date_start: '',
            date_end: '',
        }
    },
    methods: {
        attemptCreate() {
            this.createArticle();
        },
        createArticle() {

            this.$http.post('api/articles',this.article).then(
                (response) => {
                    console.log('ok');
                    this.$router.push({ path: '/articles' });
                },
                (response) => {
                    console.error(response.error);
                }
            );

        }

    }
}
</script>
