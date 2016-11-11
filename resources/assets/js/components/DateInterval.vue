<template lang="html">
  <div class="form-group">
      <div class="row">
          <div class="col-xs-12">
              <label class="control-label" for="fullname">{{interval}}</label>
              <div class="ui icon input">
                  <input type='text' class="form-control"
                      @click="openRangePicker"
                      v-model="date"
                      :name="interval"
                  />
                  <i class="calendar icon"></i>
              </div>
          </div>
       </div>
     </div>
</template>

<script lang="coffee">
    DateRangePicker = require '../mixins/DateRangePicker.vue';
    module.exports = {
        name: 'DateInterval'
        props: [ 'interval', 'date' ]
        mixins: [ DateRangePicker ]
    }
</script>

<style lang="css">
    .form-group {
        margin: 15px;
    }
</style>
