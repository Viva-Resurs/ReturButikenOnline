module.exports = {

    routes : [
        {
            path: '/',
            component: require('./pages/Welcome.vue')
        },
        {
            path: '*',
            component: require('./pages/404.vue')
        }
    ]

}
