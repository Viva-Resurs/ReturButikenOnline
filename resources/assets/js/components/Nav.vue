<template>
    <div>
        
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="/">
                    {{ $root.settings.title }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <template v-if="!$root.user">
                        <li><router-link to="/auth/login" exact>Login</router-link></li>
                        <li><a href="/register">Register</a></li>
                    </template>
                    <template v-if="$root.user">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ $root.user.name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a @click="$root.exitUser()">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>


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