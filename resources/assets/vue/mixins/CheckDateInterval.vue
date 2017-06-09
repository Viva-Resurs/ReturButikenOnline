<script lang="coffee">
    module.exports =
        name: 'CheckDateInterval'
        methods:
            checkDateInterval: (item) ->
                #Date checking because user needs a valid publish interval to activate an inactive article
                dateIsValid = false
                currentdate = new Date();
                pIntervalStart = new Date(item.publish_interval.split('|')[0]);
                pIntervalEnd = new Date(item.publish_interval.split('|')[1]);

                if(pIntervalStart < currentdate && pIntervalEnd > currentdate) #Date interval is valid
                    dateIsValid = true
                return dateIsValid     ##replace date checking in update with this method
</script>
