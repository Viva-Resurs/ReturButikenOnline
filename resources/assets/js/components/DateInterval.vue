<template lang="html">
  <div class="form-group">
      <div class="row">
          <div class="col-xs-6">
              <label class="control-label" for="fullname">Publiceringsdatum</label>
              <div class='input-group date' :id="interval+'_start'">
                  <input type='text' class="form-control" v-model="date.start"/>
                  <span class="input-group-addon">
                      <span class="fa fa-calendar"></span>
                  </span>
              </div>
          </div>
          <div class="col-xs-6">
              <label class="control-label" for="phone">Avpubliceringsdatum:</label>
              <div class='input-group date' :id="interval+'_end'">
                  <input type='text' class="form-control"  v-model="date.end"/>
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

    props: [ 'interval' ],

    data: function(){
        return {
            date: {
                start: '',
                end: ''
            },
            token: '|'
        }
    },

    methods: {
        encodeInterval(){

            bus.$emit(
                this.interval + '_changed',
                (this.date.end!='') ? this.date.start +' '+this.token+' ' + this.date.end : this.date.start
            );
            
        },
        decodeInterval(){
            return;

            if (typeof this.interval == 'undefined')
                return;

            var dates = this.interval.split(this.token);

            this.start = (dates[0]!='') ? dates[0] : '';

            this.end = (dates[1]!='') ? dates[1] : '';

        }
    },

    mounted: function() {

        this.decodeInterval();

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

        $('#'+this.interval+'_start').datetimepicker(options).on('dp.change',
            (e) => {
                this.date.start = e.date.format('YYYY-MM-DD HH:mm:ss');
                this.encodeInterval();
            }
        );

        $('#'+this.interval+'_end').datetimepicker(options).on('dp.change',
            (e) => {
                this.date.end = e.date.format('YYYY-MM-DD HH:mm:ss');
                this.encodeInterval();
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
