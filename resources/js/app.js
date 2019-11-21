require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
Vue.use(VueRouter);

//import Users from './components/users'

const routes = [
    // {
    //     path: '/',
    //     component: Users
    // }
]
const router = new VueRouter({
    routes // == routes:routes
})

const app = new Vue({
    el: '#app',
    router,//==router:router
    data: {

    },
    methods: {

    }
});
