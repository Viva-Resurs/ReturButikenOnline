<template>
    <div>
        
        <ul class="nav nav-tabs hidden-xs">
            <li><router-link to="/" exact>Home</router-link></li>
            <li><router-link to="/randomLink">'Example Undefined Link'</router-link></li>
            <li><router-link to="/articles">Articles</router-link></li>
        </ul>

        <ul class="nav visible-xs-block">
            <li><router-link to="/" exact>Home</router-link></li>
            <li><router-link to="/randomLink">'Example Undefined Link'</router-link></li>
            <li><router-link to="/articles">Articles</router-link></li>
        </ul>
        
    </div>

</template>