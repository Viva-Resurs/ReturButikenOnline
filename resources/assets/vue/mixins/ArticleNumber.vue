<script lang="coffee">
    module.exports =
        name: 'ArticleNumber'
        # SectionID - ArticleID
        # 00 - 000.000
        methods:
            ###*
            #   Decodes a encoded article number and returns a article id. 
            #   @param {artnr} articlenr to decode
            #   @return {articleId} article id
            ### 
            decodeArtNR: (artnr) ->
                articleID = Number artnr.split('-')[1].replace(/[ _.]/gi,'')
                return if !isNaN articleID then articleID else 0
            
            ###*
            #   Encodes and display a article number using a article and a section.             
            #   @param {article} article to use
            #   @param {section} section to use
            #   @return {articlenr} resulting article number
            ###
            displayArtNR: (article,section) ->
                artnr = @encodeArtNR article, section
                return artnr.replace /[-]/gi, " - "

            ###*
            #   Encodes a article number using a article and a section.
            #   @param {article} article to use
            #   @param {section} section to use
            #   @return {articlenr} resulting article number 
            ###
            encodeArtNR: (article, section) ->
                s = "000"
                n = "000.000"

                if typeof article == 'undefined'
                    return s+'-'+n

                ###*
                #   Returns a 2 digit number.
                #   @param {nr} number
                #   @return {number} number as string (2 digits)
                ###
                toString2 = (nr) ->
                    if nr < 10
                        return '0'+nr
                    return String nr

                ###*
                #   Returns a six digit number.                
                #   @param {nr} number
                #   @return {number} number as string (6 digits)
                ###
                toString6 = (nr) ->
                    nr = Number nr
                    if nr < 999999 && nr >= 100000
                        return Math.floor(nr/1000)+'.'+(nr%1000)
                    if nr < 100000 && nr >= 10000
                        return '0'+Math.floor(nr/1000)+'.'+(nr%1000)
                    if nr < 10000 && nr >= 1000
                        return '00'+Math.floor(nr/1000)+'.'+(nr%1000)
                    if nr < 1000 && nr >= 100
                        return '000.'+nr
                    if nr < 100 && nr >= 10
                        return '000.0'+nr
                    if nr < 10
                        return '000.00'+nr
                    return String nr

                if typeof article == 'object'
                    if article.sections and article.sections.length>0
                        s = toString2 article.sections[0].id
                    if article.id
                        n = toString6 article.id

                if typeof section == 'number'
                    s = toString2 section
                if typeof article == 'number'
                    n = toString6 article

                if typeof section == 'string'
                    s = toString2 Number section
                if typeof article == 'string'
                    n = toString6 Number article

                return s+'-'+n
</script>
