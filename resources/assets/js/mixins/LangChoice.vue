<script lang="coffee">
    module.exports =
        name: 'LangChoice'
        data: ->
            language_lib:
                sv: require './../lang/sv.json'
                en: require './../lang/en.json'
        methods:
            setLang: (lang) ->
                for key, l of @language_lib
                    if key == lang
                        @settings.lang = lang
                        sessionStorage.lang = lang
        created: ->
            # Set default language
            if !sessionStorage or !sessionStorage.lang
                return @setLang 'en'
            # Check if language is available
            for key, l of @language_lib
                if key == sessionStorage.lang
                    return @setLang key
            # Not available, set default language
            @setLang 'en'
</script>
