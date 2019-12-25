@extends('master')

@section('title', 'Virtual Wallet')

@section('content')

<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <router-link class="navbar-brand" to="/">Welcome</router-link>
    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">

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
                <router-link class="nav-link" to="/profile" v-if="this.$store.state.token" >Profile</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/profile/edit" v-if="this.$store.state.token" >Edit Profile</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/wallet" v-if="this.$store.state.token && this.$store.state.user.type === 'u'" >Wallet</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/movements/create/expense" v-if="this.$store.state.token && this.$store.state.user.type === 'u'" >Create Movement</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/movements/create/income" v-if="this.$store.state.token && this.$store.state.user.type === 'o'" >Create Movement</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/users" v-if="this.$store.state.token && this.$store.state.user.type === 'a'" >Users</router-link>
            </li>
        </ul>
    </div>
</nav>


{{-- <router-link to="/">Welcome</router-link>
<router-link to="/register">Register</router-link>
<router-link to="/me/edit">Edit Personal Information</router-link>
<router-link to="/login">Login</router-link> --}}

<router-view class="container" style="padding:20px;"></router-view>

@endsection
@section('pagescript')
<script src="js/app.js"></script>
 @stop
