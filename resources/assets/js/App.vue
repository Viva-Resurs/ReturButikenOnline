<template>
    <div class="container">
        <h1>This is the App.</h1>

        <navigation></navigation>

        <router-view></router-view>

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
    export default {

        name: 'App',

        components: { Navigation },

        data: function(){
            return {
                loading: true,
            }
        },

        created: function(){
            // Log a reference to this App
            console.log(this)
        }

    }
</script>
