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
            path: '/auth/register',
            component: require('./pages/auth/register.vue')
        },
        {
            path: '/articles',
            component: require('./pages/Articles.vue'),
            children: [
                {
                  path: '/',
                  component: require('./pages/articles/list.vue')
                },
                {
                  path: 'create',
                  component: require('./pages/articles/create.vue')
                },
                {
                  path: ':id',
                  component: require('./pages/articles/update.vue')
                }
            ]
        },
        {
            path: '*',
            component: require('./pages/404.vue')
        }
    ]

}
