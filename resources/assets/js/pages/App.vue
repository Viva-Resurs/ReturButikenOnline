<template>
    <div class="container">
        <h1>This is the App.</h1>

        Navigation:
        <ul class="navbar">
            <li><router-link to="/">Home</router-link></li>
            <li><router-link to="/randomLink">'Example Undefined Link'</router-link></li>
            <li><router-link to="/articles">Articles</router-link></li>
        </ul>

        View:
        <div class="panel panel-default">
            <router-view></router-view>
        </div>

    </div>
</template>

<script>
    export default {

        name: 'App',

        created: function(){
            // Log a reference to this App
            console.log(this)
        }
    }
</script>
