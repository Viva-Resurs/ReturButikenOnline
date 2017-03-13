<script lang="coffee">
    module.exports =
        methods:
            # Pagination
            rangeFilter: (ob, index, scope) ->
                scope.limitOffBtn = false

                # Show results in range
                if scope.search != ''
                    if !scope.limitOff
                        if index < scope.maxItems
                            return true
                        scope.limitOffBtn=true
                        return false
                    else
                        return true

                # Show contents in range
                return index >= scope.offset && index < scope.offset+scope.maxItems

            # Case insensitive filter
            filterBy: (item, search, targets) ->
                if search == ''
                    return true

                s = new RegExp search, "gi"

                # Check targets
                for target of targets
                    key = targets[target].key
                    if typeof item[key] == 'string' || typeof item[key] == 'number'
                        if s.test item[key]
                            return true
                    if typeof item[key] == 'object'
                        for i of item[key]
                            if s.test item[key][i].name
                                return true
                return false

            # Case insensitive filter
            filterArrayBy: (item, search, targets) ->
                if search == ''
                    return true

                s = new RegExp search, "gi"

                # Check targets
                for target in targets
                    key = target
                    if typeof item[key] == 'string' || typeof item[key] == 'number'
                        if s.test item[key]
                            return true
                    if typeof item[key] == 'object'
                        for i of item[key]
                            if s.test item[key][i].name
                                return true
                return false

            # Sort objects by targeting a deeper property
            deepSort: (a, b, order, desc) ->
                checkA = a
                checkB = b

                # Check Arguments
                if !checkA? || !checkB?
                    return 0

                # Not the same type?
                if typeof checkA != typeof checkB
                    return 0

                # Dig down to the properties to compare
                levels = order.split '.'

                for l of levels
                    if !checkA[levels[l]]? || !checkB[levels[l]]?
                        return 0
                    checkA = checkA[levels[l]]
                    checkB = checkB[levels[l]]

                # Compare numbers
                if typeof checkA == 'number'
                    if checkA == checkB
                        return 0
                    return (checkA - checkB) * desc

                # Compare strings
                if typeof checkA == 'string'
                    checkA = checkA.toLowerCase()
                    checkB = checkB.toLowerCase()

                    # Char by char
                    for index of checkA
                        if checkA[index] < checkB[index]
                            return -1 * desc
                        if checkA[index] > checkB[index]
                            return 1 * desc
                    return 0

                # Compare arrays
                if typeof checkA == 'object'

                    # Compare lengths
                    if checkA.length != checkB.length
                        return checkA.length - checkB.length * desc;

                    # Compare contents (.name)
                    for key of checkA
                        # Nothing to compare
                        if !checkA[key].name && !checkB[key].name
                            return 0

                        # Equal
                        if checkA[key].name == checkB[key].name
                            return 0

                        # Char by char
                        for index of checkA[key].name
                            if checkA[key].name[index] < checkB[key].name[index]
                                return -1 * desc;
                            if checkA[key].name[index] > checkB[key].name[index]
                                return 1 * desc
                        return 0

            # Set order & toggle desc
            setOrder: (what, desc) ->
                @desc = if @order == what then @desc*=-1 else if desc then -1 else 1
                @order = what || 'updated_at'

            # Force rerender
            updateList: (list) ->
                list.reverse()
                list.reverse()

        data: ->
            search: ''

            limitOff: false
            limitOffBtn: false

            order: ''
            desc: -1

            offset: 0
            maxItems: 10

        watch:
            # Reset show all results when editing search
            search: (val, oldVal) ->
                @offset = 0
                @limitOff = false

            # Reset offset when changing maxItems
            maxItems: (val, oldVal) ->
                @offset = 0

        components:
            Pagination: require '../components/Pagination.vue'

        created: ->
            bus.$on 'offset_changed', (new_offset) => @offset = new_offset
            bus.$on 'limit_changed', (new_limit) => @maxItems = new_limit

        beforeDestroy: ->
            bus.$off 'offset_changed'
            bus.$off 'limit_changed'
</script>
