<script>
    export default {
        inserted: function(el){
            $(el).daterangepicker(
                // TODO: Switch between singleDatePicker:true|false
                // TODO: Maybe option to reset range/date
                // Options
                {
                    locale: {
                        format: "YYYY-MM-DD hh:mm:ss",
                        separator: " | "
                    },
                    timePicker: true,
                    opens: 'left',
                    timePicker24Hour: true,
                    timePickerSeconds: true,
                    ranges: {
                            "Today": [
                                "2016-10-25T11:52:21.389Z",
                                "2016-10-25T11:52:21.389Z"
                            ],
                            "Last 7 Days": [
                                "2016-10-19T11:52:21.389Z",
                                "2016-10-25T11:52:21.389Z"
                            ],
                            "This Month": [
                                "2016-09-30T22:00:00.000Z",
                                "2016-10-31T22:59:59.999Z"
                            ],
                            "Clear": [
                                "0000-00-00T00:00:00.000Z",
                                "0000-00-00T00:00:00.000Z"
                            ]
                    },
                },
                // Callback
                (start,end,label)=>{
                    console.log('emit')
                    bus.$emit(
                        el.name+'_changed',
                        el.id,
                        start.format('YYYY-MM-DD hh:mm:ss') + ' | ' + end.format('YYYY-MM-DD hh:mm:ss')
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
