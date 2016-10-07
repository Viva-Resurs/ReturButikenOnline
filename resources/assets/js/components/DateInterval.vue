<template lang="html">
  <div class="form-group">
      <div class="row">
          <div class="col-xs-6">
              <label class="control-label" for="fullname">Publiceringsdatum</label>
              <div class='input-group date' id='datetimepicker1'>
                  <input type='text' class="form-control" v-model='date_pub1'/>
                  <span class="input-group-addon">
                      <span class="fa fa-calendar"></span>
                  </span>
              </div>
          </div>
          <div class="col-xs-6">
              <label class="control-label" for="phone">Avpubliceringsdatum:</label>
              <div class='input-group date' id='datetimepicker2'>
                  <input type='text' class="form-control"  v-model='date_pub2'/>
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
  props: [
    "date_pub1",
    "date_pub2"
  ],
  mounted: function() {
    var options = {
               showTodayButton: true,
               toolbarPlacement: 'top',
               calendarWeeks: true,
               showClose: true,
               allowInputToggle: true,
               minDate: moment().hour(0).minute(0).subtract(1,'d'),
               maxDate: moment().hour(0).minute(0).add(5,'y'),
               locale: moment.locale('sv'),
               icons: {
                   time: 'fa fa-clock-o',
                   date: 'fa fa-calendar',
                   up: 'fa fa-chevron-up',
                   down: 'fa fa-chevron-down',
                   previous: 'fa fa-chevron-left',
                   next: 'fa fa-chevron-right',
                   today: 'fa fa-crosshairs',
                   clear: 'fa fa-trash',
                   close: 'fa fa-times'
               }
           };

  $('.date').datetimepicker(options);


  }
}
</script>

<style lang="css">
  .form-group {
    margin: 15px;
  }
</style>
