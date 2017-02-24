<script lang="coffee">
    module.exports =
        name: 'Translation'
        methods:
            translate: (label) ->
                # Use this data
                lang = @$root.language_lib[@$root.settings.lang]

                # If using a sub label
                if label.indexOf('.') != -1

                    # Sub labels are defined like this: category.label
                    c = label.split('.')[0]
                    l = label.split('.')[1]

                    # Try to find a match
                    if lang[c] && lang[c][l] != undefined
                        return '⚐ '+lang[c][l]
                    else
                        console.info 'Missing sub-lang-data for '+label
                        return label

                # Try to find a match
                if lang[label] != undefined
                    return '⚐ '+lang[label]
                else
                    console.info 'Missing lang-data for '+label
                    return label
</script>
