@extends('master')

@section('title', 'Virtual Wallet')

@section('content')

<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <router-link class="navbar-brand" to="/">Virtual Wallet</router-link>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">

        </ul>
        <ul class="nav navbar-nav navbar-right" >

            <li class="nav-item">
                <router-link  class="nav-link" to="/login" v-if="!this.$store.state.token">Login</router-link>
            </li>
            <li>
                <router-link  class="nav-link" to="/register" v-if="!this.$store.state.token">Register</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/movements/create/expense" v-if="this.$store.state.token && this.$store.state.user.type === 'u'" >Create Expense</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/movements/create/income" v-if="this.$store.state.token && this.$store.state.user.type === 'o'" >Create Income</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/users/create" v-if="this.$store.state.token && this.$store.state.user.type === 'a'" >Create Users</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/users" v-if="this.$store.state.token && this.$store.state.user.type === 'a'" >Users</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/statistics" v-if="this.$store.state.token && this.$store.state.user.type === 'a'" >Statistics</router-link>
            </li>

            {{-- Dropdown --}}
            <li class="nav-item dropdown" v-if="this.$store.state.token">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                  Account

                </a>
                <div class="dropdown-menu">
                    <router-link class="dropdown-item" to="/profile" v-if="this.$store.state.token" >Profile</router-link>
                    <router-link class="dropdown-item" to="/profile/edit" v-if="this.$store.state.token" >Edit Profile</router-link>
                    <router-link class="dropdown-item" to="/wallet" v-if="this.$store.state.token && this.$store.state.user.type === 'u'" >Wallet</router-link>
                    <div class="dropdown-divider"></div>
                    <router-link  class="dropdown-item" to="/logout" v-if="this.$store.state.token">Logout</router-link>
                </div>
            </li>
            <li class="nav-item dropdown" v-if="this.$store.state.token">
                <div v-if="this.$store.state.user.photo !== null">
                    <img
                      :src="`./storage/fotos/${this.$store.state.user.photo}`"
                      style="border-radius: 50%; height:40px;width:40px;"
                    />
                </div>
                <div v-else>
                    <img :src="`./storage/fotos/default.png`" style="border-radius: 50%; height:40px;width:40px;"  height="50px" width="50px" />
                </div>
            </li>
        </ul>
    </div>
</nav>

<router-view class="container" style="padding:20px;"></router-view>

@endsection
@section('pagescript')
<script src="js/app.js"></script>
{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
 @stop
