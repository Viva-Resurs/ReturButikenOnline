<template>
<div id="articleForm">
    <div class="panel-heading">
        Skapa artikel
    </div>

    <form class="form-vertical" v-on:submit.prevent="attemptCreate" role="form" name="myform">

        <div class="panel-body">

            <div class="form-group">
              <div class="row">
                <div class="col-xs-8">
                  <label class="control-label" for="name">Varunamn:</label>
                  <input type="email" class="form-control" v-model="article.name" id="name" placeholder="Varunamn">
                </div>

                <div class="col-xs-4">
                  <label class="control-label" for="category">Välj varukategori:</label>
                  <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="category" data-toggle="dropdown">
                        Kategori<span class="caret"></span></button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="category">
                          <li role="presentation"><a role="menuitem" href="#">Category item</a></li>
                      </ul>
                  </div>
                </div>

              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-xs-12">
                <label class="control-label" for="desc">Beskrivning av varan:</label>
                <textarea rows="4" class="form-control" v-model="article.desc" id="desc" placeholder="Ge din beskrivning här"></textarea>
              </div>
              </div>
            </div>

            <div class="form-group">
                <div class="row">
                  <div class="col-xs-12">
                  <label class="checkbox-inline">
                    <input type="checkbox" value="" v-model="article.public"> Publicera inom datumintervall
                  </label>
                  <label class="checkbox-inline" >
                    <input type="checkbox" value="" v-model="article.public"> Aktivera budgivning
                  </label>
                </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                  <div class="col-xs-12">
                  <label class="radio-inline">
                    <input type="radio" name="publish" value="" v-model="article.publish_interval"> Publicera på kommunens Intranät
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="bidding" value="" v-model="article.bidding_interval"> Publicera för allmänheten
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
export default {
    name: 'Create',
    data() {
        return {
            article: {
            },
            myform: []
        }
    },
    methods: {
        attemptCreate() {

        },
        createArticle() {
            var self = this;

        }

    }
}
</script>
