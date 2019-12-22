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
    path: '/users/me/profile',
    component: Profile
},
{
    path: '/users/me/edit',
    component: EditProfile
},
{
    path: '/users/me/wallet',
    component: Wallet
},
{
    path: '/users/me/wallet/statistics',
    component: WalletStats
},
{
    path: '/users',
    component: Users
},
{
    path: '/users/me/movements/create',
    component: CreateMovement
}
]
const router = new VueRouter({
    //mode: 'history',
    routes // == routes:routes
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
