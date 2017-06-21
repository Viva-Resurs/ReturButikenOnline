    <!-- Active.vue handles the actions of the active/inactive button in the articles toolbar.
        It changes the binary .is_active state of an article.
        The toolbar as a whole is defined in pages\articles\list.vue-->
<template lang="pug">
        div.ui.icon.button.hover-default(
            style="background-color: #FFF; border-top: 1px solid rgb(212,212,213); border-bottom: 1px solid rgb(212,212,213); border-left: 1px solid rgb(212,212,213)"
            v-tooltip = ""
            ":data-html" = `
                (item.is_active) ? translate('tool.active') : translate('tool.inactive')
            `
            @click    = "update(item)"
            ":class" = `
                (item.is_active) ? 'active-interval':''
            `
        )
            i.ui.icon.check.circle.icon-style(v-if="item.is_active")
            i.ui.icon.minus.circle.icon-style(v-else="")
</template>

<script lang="coffee">
    module.exports =
        name: 'Active'
        props: [ 'item', 'from' ]
        methods:
            update: (item) ->
                if item.is_active
                    item.is_active = false
                    bus.$emit @from + '_item_changed', item
                else
                    dateIsValid = @checkDateInterval(item)
                    if(dateIsValid == true)
                        item.is_active = true
                        bus.$emit @from + '_item_changed', item
                    else #User needs to set new publish interval if date interval is not valid
                        item = @item
                        bus.$emit 'show_message',
                            title: @$root.translate('publish_interval.select_date_title'),
                            message:'',
                            start: item.publish_interval.split('|')[0],
                            end: item.publish_interval.split('|')[1]
                            type:'calendar',
                            cb: ( start, end ) -> #cb (callback) sends an asynchronous emit to corresponding
                                                    #method in pages\articles\list.vue
                                bus.$emit(
                                    'publish_interval_changed',
                                    item.id,
                                    start.format('YYYY-MM-DD HH:mm:ss') + ' | ' + end.format('YYYY-MM-DD HH:mm:ss')
                                )
                                dateIsValid = false
                                currentdate = new Date();
                                pIntervalStart = new Date(item.publish_interval.split('|')[0]);
                                pIntervalEnd = new Date(item.publish_interval.split('|')[1]);

                                if(pIntervalStart < currentdate && pIntervalEnd > currentdate) #Date interval is valid
                                    dateIsValid = true
                                if(dateIsValid == true) #Checks interval again
                                    item.is_active = true
                                    bus.$emit @from + '_item_changed', item
</script>
