<script lang="coffee">
    module.exports =
        name: 'Translation'
        methods:
            ###*
            #   Translates a label using a defined language and returns it.
            #   @param {label} original label
            #   @return {label} translation from label 
            ###
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
                        if process.env.NODE_ENV == "development"
                            return lang[c][l]
                        return lang[c][l]
                    else
                        if process.env.NODE_ENV == "development"
                            console.warn 'Missing sub-lang-data for '+label
                        return label

                # Try to find a match
                if lang[label] != undefined
                    if process.env.NODE_ENV == "development"
                        return lang[label]
                    return lang[label]
                else
                    if process.env.NODE_ENV == "development"
                        console.warn 'Missing lang-data for '+label
                    return label
            
            ###*
            #   Get the language variable for translations.
            #   @return {lang} language variable
            ###
            getLanguage: () ->            
                return @$root.settings.lang
</script>
