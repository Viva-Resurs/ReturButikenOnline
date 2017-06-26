<script lang="coffee">
    module.exports =
        name: 'From'
        computed:
            getFrom: ->
                if @$route && @$route.path
                    pathDivided =  @$route.path.split('/')
                    lastPath = pathDivided[pathDivided.length-1]
                    if lastPath == ""
                        lastPath = "ui"
                    return lastPath

        methods:
            isAdmin: (level) ->
                if @user and @user.roles
                    for role in @user.roles
                        if role.name == 'admin'
                            return true
                        if role.name == 'sectionadmin' and level==2
                            return true
                return false
</script>
