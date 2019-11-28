@extends('master')

@section('title', 'Virtual Wallet')

@section('content')

<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <router-link class="navbar-brand" to="/">Welcome</router-link>
    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                {{-- <router-link class="nav-link" to="/" >Profile</router-link> --}}
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item">
                <router-link  class="nav-link" to="/login" v-if="!this.$store.state.token">Login</router-link>
                <router-link  class="nav-link" to="/logout" v-if="this.$store.state.token">Logout</router-link>
            </li>
            <li>
                <router-link  class="nav-link" to="/register" v-if="!this.$store.state.token">Register</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/users/me/profile" v-if="this.$store.state.token" >Profile</router-link>
            </li>

            <li class="nav-item">
                <router-link class="nav-link" to="/users/me/edit" v-if="this.$store.state.token" >Edit Profile</router-link>
            </li>
        </ul>
    </div>
</nav>


{{-- <router-link to="/">Welcome</router-link>
<router-link to="/register">Register</router-link>
<router-link to="/me/edit">Edit Personal Information</router-link>
<router-link to="/login">Login</router-link> --}}

<router-view style="padding:20px;"></router-view>

@endsection
@section('pagescript')
<script src="js/app.js"></script>
 @stop
