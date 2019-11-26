require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import store from './stores/store';

Vue.use(VueRouter);


import Welcome from './components/welcome'
import Register from './components/register'
import Login from './components/login'
import Logout from './components/logout'
import Profile from './components/profile'

const routes = [{
    path: '/',
    component: Welcome
}, {
    path: '/register',
    component: Register
}, {
    path: '/login',
    component: Login
},
{
    path: '/logout',
    component: Logout
},
{
    path: '/profile',
    component: Profile
}]
const router = new VueRouter({
    //mode: 'history',
    routes // == routes:routes
})

const app = new Vue({
    el: '#app',
    router,//==router:router
    store,
    data: {

    },
    methods: {

    },
    created() {
        this.$store.commit('loadTokenAndUserFromSession');
    }
});
