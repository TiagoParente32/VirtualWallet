require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
Vue.use(VueRouter);

import Welcome from './components/welcome'
import Register from './components/register'

const routes = [{
    path: '/',
    component: Welcome
}, {
    path: '/register',
    component: Register
}]
const router = new VueRouter({
    //mode: 'history',
    routes // == routes:routes
})

const app = new Vue({
    el: '#app',
    router, //==router:router
    data: {

    },
    methods: {

    }
});
