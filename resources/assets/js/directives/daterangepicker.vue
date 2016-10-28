<script>
    export default {
        inserted: function(el){
            // Generate some ranges
            var now = moment();
            var day = moment().hours(18).minutes(0).seconds(0);
            var week = moment().add(1, 'week').hours(18).minutes(0).seconds(0);
            var month = moment().add(1, 'months').hours(18).minutes(0).seconds(0);
            $(el).daterangepicker(
                // Options
                {
                    locale: {
                        format: "YYYY-MM-DD HH:mm:ss",
                        separator: " | "
                    },
                    timePicker: true,
                    opens: 'left',
                    timePicker24Hour: true,
                    timePickerSeconds: true,
                    ranges: {
                            "Today only": [
                                now,
                                day
                            ],
                            "Today + 7 Days": [
                                now,
                                week
                            ],
                            "Today + 1 Month": [
                                now,
                                month
                            ]
                    },
                },
                // Callback
                (start,end,label)=>{
                    console.log('emit')
                    bus.$emit(
                        el.name+'_changed',
                        el.id,
                        start.format('YYYY-MM-DD HH:mm:ss') + ' | ' + end.format('YYYY-MM-DD HH:mm:ss')
                    );
                }
            );
            // Update interval on init
            var interval = (typeof $(el).data('value') == 'string') ? $(el).data('value').split('|') : [];
            if (interval.length == 2) {
                var startDate = interval[0] || '';
                var endDate = interval[1] || '';
                $(el).data('daterangepicker').setStartDate(startDate);
                $(el).data('daterangepicker').setEndDate(endDate);
            }
        }
    };
</script>
