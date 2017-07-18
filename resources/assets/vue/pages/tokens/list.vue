<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div.ui.grid.one.column( v-else="" )
            div.column
                div.ui.dividing.header.fluid {{ translate('tokens.header') }}
            token-list.column(                 
                ":toolsRow"=`[
                    $options.components.Remove,
                ]`
                ":items"="items"
                )        
</template>

<script lang="coffee">
    module.exports =
        name: 'List'
        components:
            TokenList: require '../../components/token/list.vue'            
            Remove: require '../../components/tools/Remove.vue'          
        data: ->
            items: []
            columns: [ 'token' ]

        methods:
            ###*
            #   Sends a token create request to backend.             
            ###
            createToken: () ->
                @$http.post('api/tokens').then(
                    (response) =>                        
                        token = response.data
                        @items.push token
                        @$nextTick ->
                            $('tbody').trigger 'updated', token.id
                    (response) => bus.$emit 'error', response.data
                )

            ###*
            #   Attempt to remove a token. 
            #   @param {token} token to remove
            ###
            attemptRemove: (token) ->
                @removeToken token

            ###*
            #   Removes a token from the backend. 
            #   @param {token} token to remove  
            ###
            removeToken: (token) ->
                @$http.delete('api/tokens/'+token.id).then(
                    (response) =>
                        $('tbody').trigger 'removed', token.id, ->
                            Vue.set token, 'removed', true
                    (response) => bus.$emit 'error', response.data
                )

            ###*
            #   Gets a list of tokens from backend.
            ###
            getTokens: ->
                @$root.loading = true;
                @$http.get('api/tokens').then(
                    (response) =>
                        @items = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

        created: ->
            @getTokens()                       
            bus.$on 'tokens_item_add', => @createToken()
            bus.$on 'tokens_item_remove', (item) => @attemptRemove item
            
        beforeDestroy: ->
            bus.$off 'tokens_item_add'
            bus.$off 'tokens_item_remove'
</script>
