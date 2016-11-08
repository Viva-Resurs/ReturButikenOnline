<script>
    export default {
        inserted: function(el){
            // Get current interval on init
            var interval = (typeof $(el).data('value') == 'string') ? $(el).data('value').split('|') : [];
            var startDate = interval[0] || false;
            var endDate = interval[1] || false;

            // Generate some ranges
            var now = moment();
            var day = moment().hours(18).minutes(0).seconds(0);
            var week = moment().add(1, 'week').hours(18).minutes(0).seconds(0);
            var month = moment().add(1, 'months').hours(18).minutes(0).seconds(0);

            // Start the daterangepicker
            $(el).daterangepicker(
                // Options
                {
                    format: "YYYY-MM-DD HH:mm:ss",
                    separator: " | ",
                    timePicker: true,
                    opens: 'left',
                    timePicker12Hour: false,
                    timePickerSeconds: true,
                    startDate: startDate,
                    endDate: endDate,
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
                (start,end,label) => {
                    bus.$emit(
                        el.name+'_changed',
                        el.id,
                        start.format('YYYY-MM-DD HH:mm:ss') + ' | ' + end.format('YYYY-MM-DD HH:mm:ss')
                    );
                }
            );
        }
    };
</script>
