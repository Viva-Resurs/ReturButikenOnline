<script>
    export default {
        methods: {
            openRangePicker: function(e){

                // Get the button/input element
                var el = e.target;
                if (el.nodeName=='I')
                    el = el.parentElement;

                // Trigger a click on the picker
                $(el.drp).trigger('click');

                // If already initiated, let it be
                if (el.drp)
                    return;
                //if ($(el.drp).data('daterangepicker'))
                //    return;

                // Create a hidden element
                el.drp = document.createElement('div');
                el.drp.classList.add('hidden');
                el.drp.style.top = '10%';
                el.drp.style.width= '100%';
                el.drp.style.position = 'fixed';

                $('.offset_container').append(el.drp);

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
                $(el.drp).daterangepicker(
                    // Options
                    {
                        format: "YYYY-MM-DD HH:mm:ss",
                        separator: " | ",
                        timePicker: true,
                        opens: 'center',
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

                // Page dimmer
                $(el.drp).on('show.daterangepicker',(e)=>{
                    $('.ui.page.dimmer').addClass('active');
                });
                $(el.drp).on('hide.daterangepicker',(e)=>{
                    $('.ui.page.dimmer').removeClass('active');
                });

                // Trigger the daterangepicker to open first time
                $(el.drp).trigger('click');

            }
        }
    };
</script>
