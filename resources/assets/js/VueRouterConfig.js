module.exports = {

    routes : [
        {
            path: '/',
            component: require('./pages/Welcome.vue')
        },
        {
            path: '/articles',
            component: require('./pages/Articles.vue')
        },
        {
            path: '/articles/create',
            component: require('./pages/articles/create.vue')
        },
        {
            path: '*',
            component: require('./pages/404.vue')
        }
    ]

}
