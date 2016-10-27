<template lang="html">
  <div class="form-group">
      <div class="row">
          <div class="col-xs-12">
              <label class="control-label" for="fullname">{{interval}}</label>
              <div class='input-group date'>
                  <input type='text' class="form-control"
                      v-model="date"
                      v-daterangepicker :name="interval"/>
                  <span class="input-group-addon">
                      <span class="fa fa-calendar"></span>
                  </span>
              </div>
          </div>
       </div>
     </div>
</template>

<script>
    export default {
        name: 'DateInterval',
        props: [ 'interval', 'date' ]
    }
</script>

<style lang="css">
    .form-group {
        margin: 15px;
    }
</style>
