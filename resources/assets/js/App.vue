<template>
    <div>
        <navigation></navigation>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    /**
     * Create a event-central
     */
    window.bus = new Vue();

    /**
     * Define global components
     */
    Vue.component('loading', require('./components/Loading.vue'));

    /**
     * Export the root-instance options
     */
    import Navigation from './components/Nav.vue'
    import Auth from './mixins/Auth.vue'
    import ErrorHandler from './mixins/ErrorHandler.vue'
    export default {

        name: 'App',

        components: { Navigation },

        mixins: [ Auth, ErrorHandler ],

        data: function(){
            return {
                loading: true,

                settings: {
                    title: 'ReturButikenOnline'
                }
            }
        },

        created: function(){
            // Log a reference to this App
            console.log(this)
        }

    }
</script>
