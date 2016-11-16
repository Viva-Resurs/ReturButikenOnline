<template>

    <div class="ui stackable secondary pointing menu">

        <router-link to="/" class="item" exact>
            {{ $root.settings.title }}
        </router-link>

        <div class="ui right secondary stackable menu">
            <template v-if="!$root.user">
                <router-link to="/auth/login" exact class="item">Login</router-link>

                <router-link to="/auth/register" exact class="item">Register</router-link>

                <router-link to="/help" class="item">Hjälp</router-link>
            </template>

            <template v-if="$root.user">
                <router-link to="/articles/create" exact class="item">Publicera</router-link>

                <router-link to="/articles" exact class="item">Arkiv</router-link>

                <router-link to="/categories" exact class="item">Kategorier</router-link>

                <router-link to="/users" exact class="item">Användare</router-link>

                <a class="ui dropdown item" v-dropdown>
                        {{ $root.user.name }}
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item">Profil</div>
                            <a class="item" @click="$root.exitUser()">Logout</a>
                        </div>
                </div>

            </template>

        </div>

    </div>

</template>

<script>
    export default {
        name: 'Nav'
    }
</script>
