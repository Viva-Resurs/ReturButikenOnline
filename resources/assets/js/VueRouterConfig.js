module.exports = {

    linkActiveClass: 'active',
    mode: 'history',

    routes : [
        {
            path: '/',
            component: require('../vue/pages/Overview.vue'),
            meta: {  },
            children: [
                {
                  path: '/',
                  component: require('../vue/pages/overview/list.vue')
                }
            ]
        },
        {
            path: '/auth/login',
            component: require('../vue/pages/auth/login.vue')
        },
        {
            path: '/articles',
            component: require('../vue/pages/Articles.vue'),
            meta: { requiresAuth: true },
            children: [
                {
                  path: '/',
                  component: require('../vue/pages/articles/list.vue')
                },
                {
                  path: 'create',
                  component: require('../vue/pages/articles/create.vue')
                },
                {
                  path: ':id',
                  component: require('../vue/pages/articles/update.vue')
                }
            ]
        },
        {
            path: '/categories',
            component: require('../vue/pages/Categories.vue'),
            meta: { requiresAuth: true },
            children: [
                {
                  path: '/',
                  component: require('../vue/pages/categories/list.vue')
                }
            ]
        },
        {
            path: '/sections',
            component: require('../vue/pages/Sections.vue'),
            meta: { requiresAuth: true },
            children: [
                {
                  path: '/',
                  component: require('../vue/pages/sections/list.vue')
                }
            ]
        },
        {
            path: '/users',
            component: require('../vue/pages/Users.vue'),
            meta: { requiresAuth: true },
            children: [
                {
                  path: '/',
                  component: require('../vue/pages/users/list.vue')
                },
                {
                  path: 'create',
                  component: require('../vue/pages/users/create.vue')
                },
                {
                  path: 'profile',
                  component: require('../vue/pages/users/profile.vue')
                },
                {
                  path: ':id',
                  component: require('../vue/pages/users/update.vue')
                }
            ]
        },
        {
            path: '/:artnr',
            component: require('../vue/pages/articles/show.vue'),
        },
        {
            path: '*',
            component: require('../vue/pages/404.vue')
        }
    ]

};
