module.exports = {

    routes : [
        {
            path: '/',
            component: require('./pages/Welcome.vue')
        },
        {
            path: '/auth/login',
            component: require('./pages/auth/login.vue')
        },
        {
            path: '/articles',
            component: require('./pages/Articles.vue'),
            children: [
                {
                  path: '/',
                  component: require('./pages/articles/index.vue')
                },
                {
                  path: 'create',
                  component: require('./pages/articles/create.vue')
                }
            ]
        },
        {
            path: '*',
            component: require('./pages/404.vue')
        }
    ]

}
