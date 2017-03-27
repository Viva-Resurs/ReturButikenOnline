<script lang="coffee">
    module.exports =
        name: 'From'
        computed:
            from: ->
                if @$route && @$route.path then @$route.path.substring @$route.path.indexOf('ui/')+3 
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
