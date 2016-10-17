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
                <router-link to="/" class="navbar-brand">{{ $root.settings.title }}</router-link>
            
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
                        <li class="navbar-link"><router-link to="/auth/login" exact>Login</router-link></li>
                        <li class="divider-vertical"></li>
                        <li class="navbar-link"><router-link to="/auth/register" exact>Register</router-link></li>
                        <li class="divider-vertical"></li>
                        <li class="navbar-link"><router-link to="/help">Hjälp</router-link></li>
                    </template>
                    <template v-if="$root.user">
                        <li class="navbar-link"><router-link to="/articles/create" exact>Publicera</router-link></li>
                        <li class="divider-vertical"></li>
                        <li class="navbar-link"><router-link to="/articles" exact>Arkiv</router-link></li>
                        <li class="divider-vertical"></li>
                        <li class="navbar-link"><router-link to="/categories" exact>Kategorier</router-link></li>
                        <li class="divider-vertical"></li>
                        <li class="navbar-link"><router-link to="/articles" exact>Användare</router-link></li>
                        <li class="divider-vertical"></li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ $root.user.name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a role="button">Profil</a></li>
                                <li><a role="button" @click="$root.exitUser()">Logout</a></li>
                            </ul>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>

    <template v-if="false">
        <ul class="nav nav-tabs hidden-xs">
            <li><router-link to="/" exact>Home</router-link></li>
            <template v-if="$root.user">
                <li><router-link to="/randomLink">'Example Undefined Link'</router-link></li>
                <li><router-link to="/articles">Articles</router-link></li>
            </template>
        </ul>

        <ul class="nav visible-xs-block">
            <li><router-link to="/" exact>Home</router-link></li>
            <template v-if="$root.user">
                <li><router-link to="/randomLink">'Example Undefined Link'</router-link></li>
                <li><router-link to="/articles">Articles</router-link></li>
            </template>
        </ul>
    </template>

    </div>

</template>
