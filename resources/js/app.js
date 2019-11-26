require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import store from './stores/store';

Vue.use(VueRouter);


import Welcome from './components/welcome'
import Register from './components/register'
import EditProfile from './components/editprofile'


//const token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiMWVjMzE3ZDA2ZGY4NjhhYjk1YTdiMzczYjZmNGRiODY0OGNlODcyMzU0YjE1MjdiZjZjMDVhODk4ODU3MzkyOTUxMDQ1MTFiNWVmYmZkMzkiLCJpYXQiOjE1NzQxOTE1MDMsIm5iZiI6MTU3NDE5MTUwMywiZXhwIjoxNjA1ODEzOTAzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.ESvJn73rKffAsp1D1iuj3XZERXbBInHL_dOBCIPjh_oA-f-NTICE389hszeVU5Y3t_xbQkiEoaXvPDOeDb_sxmMfuPTARFDFi7IOmxrXvgKXknBEv5n8XSiVSTyPgLp9Kx1a0e8xE4tmNlWMWdNS2LHAmLl92pcs3biKVeaPcyQ-9fKgQrWSzTLiFYQUA1SQUVIBwjnghZ1cTN6Pc5wReGCPIc7UUOnKhiA_gQj1vVHJ2EXoaEs-v_wNvK7wJjrdFlSDnlQFPNz4eCHNpwEtIKCdUNdm-QOw5S1N3FQW8Te8M64OGAOtqCMjMKa-5EfzTT39PjuN5lWtpa_I6pdZFJZI_cOetJNiAudqwiV6T4s6oWOId5jpVIfP2hNqynpohEnfhQlcP-123yeOtP_ugdIKML8T7vjS5tO_4XpA8pgRzmXVL6iU5nMj_RLhzxEfUHGJEtykUbOPfS2KK1DYzgc6XZzJrOFpPzHs6Ar4peA28pjqa9K4RekonA5fVMtUQifJn_nCsfKQP9zDebuoG-kqYfBUNXGJfF9k0r8avNnqZnuR6QQ2dVFUfb39uJGmoXkWfZeinesOcANnNHShETRM0YLnsdDs5zsuJfFlqJI6Bs0YHYayYJ-Qb5oCifmYoV660rjAVuaC4_oHHB6NN5asiu22teDj52YrPWo_0fs";
//axios.defaults.headers.common.Authorization = "Bearer " + token;
import Login from './components/login'
import Logout from './components/logout'
import Profile from './components/profile'

const routes = [{
    path: '/',
    component: Welcome
}, {
    path: '/register',
    component: Register
},{
    path: '/me/edit',
    component: EditProfile
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
