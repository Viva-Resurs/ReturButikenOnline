<script lang="coffee">
    module.exports =
        name: 'From'
        computed:
            ###*
            #   Returns the page recently navigated from.
            #   @return {lastPath} page last navigated from
            ###
            getFrom: ->
                if @$route && @$route.path
                    pathDivided =  @$route.path.split('/')
                    lastPath = pathDivided[pathDivided.length-1]
                    if lastPath == ""
                        lastPath = "ui"
                    return lastPath

        methods:
            ###*
            #   Returns if the user either is a admin or a sectionadmin.
            #   @return {boolean} true if admin or sectionadmin, otherwise false 
            ###
            isAdmin: (level) ->
                if @user and @user.roles
                    for role in @user.roles
                        if role.name == 'admin'
                            return true
                        if role.name == 'sectionadmin' and level==2
                            return true
                return false
</script>
