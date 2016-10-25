<template lang="html">
  <div class="form-group">
      <div class="row">
          <div class="col-xs-12">
              <label class="control-label" for="fullname">{{interval}}</label>
              <div class='input-group date'>
                  <input type='text' :id="interval" class="form-control" :value="date"/>
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

        props: [ 'interval', 'date' ],

        mounted: function() {

            $('#'+this.interval).daterangepicker(
                // Options
                {
                    locale: {
                        format: "YYYY-MM-DD hh:mm:ss",
                        separator: " | "
                    },
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: true
                },
                // Callback
                (start,end,label)=>{
                    bus.$emit(
                        this.interval + '_changed', start.format('YYYY-MM-DD hh:mm:ss') + ' | ' + end.format('YYYY-MM-DD hh:mm:ss')
                    );
                }
            );

        }

    }
</script>

<style lang="css">
    .form-group {
        margin: 15px;
    }
</style>
