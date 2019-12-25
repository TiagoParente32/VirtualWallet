require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import store from './stores/store';

//import BootstrapVue from 'bootstrap-vue'

//Vue.use(BootstrapVue)
import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate)

//Vue.use(BootstrapVue)

Vue.use(VueRouter);

Vue.use(new VueSocketIO({
    debug: true,
    connection: 'http://127.0.0.1:8080'
}));

import VueSocketIO from "vue-socket.io";
import Welcome from './components/welcome'
import Register from './components/register'
import EditProfile from './components/editprofile'
import Login from './components/login'
import Logout from './components/logout'
import Profile from './components/profile'
import Wallet from './components/wallet'
import Users from './components/usersList'
import WalletStats from './components/walletStats';
import CreateMovement from './components/createMovement';

function requireAuth(to, from, next) {
    if (sessionStorage.getItem('token') != null) {
        next();
    } else {
        next('/login');
    }
}
function requireNoAuth(to, from, next) {
    if (sessionStorage.getItem('token') == null) {
        next();
    } else {
        next('/profile');
    }
}
function onlyAdmins(to, from, next) {
    requireAuth(to, from, next);
    let user = JSON.parse(sessionStorage.getItem('user'));
    console.log(user);
    if (user.type == 'a') {
        next();
    } else {
        next('/profile');
    }
}

function onlyOperators(to, from, next) {
    requireAuth(to, from, next);
    let user = JSON.parse(sessionStorage.getItem('user'));
    if (user.type === 'o' && user.active == 1) {
        next();
    } else {
        next('/profile');
    }
}

function onlyUsers(to, from, next) {
    requireAuth(to, from, next);
    let user = JSON.parse(sessionStorage.getItem('user'));
    if (user.type === 'u' && user.active == 1) {
        next();
    } else {
        next('/profile');
    }
}


const routes = [{
    path: '/',
    component: Welcome
}, {
    path: '/register',
    component: Register,
    beforeEnter: requireNoAuth
}, {
    path: '/login',
    component: Login,
    beforeEnter: requireNoAuth
},
{
    path: '/logout',
    component: Logout,
    beforeEnter: requireAuth
},
{
    path: '/profile',
    component: Profile,
    beforeEnter: requireAuth
},
{
    path: '/profile/edit',
    component: EditProfile,
    beforeEnter: requireAuth
},
{
    path: '/wallet',
    component: Wallet,
    beforeEnter: onlyUsers
},
{
    path: '/wallet/statistics',
    component: WalletStats,
    beforeEnter: onlyUsers
},
{
    path: '/movements/create',
    component: CreateMovement,
    beforeEnter: onlyUsers
},
{
    path: '/users',
    component: Users,
    beforeEnter: onlyAdmins

}
]
const router = new VueRouter({
    //mode: 'history',
    routes
})

const app = new Vue({
    el: '#app',
    router, //==router:router
    store,
    data: {

    },
    methods: {

    },
    created() {
        this.$store.commit('loadTokenAndUserFromSession');
    }
});
