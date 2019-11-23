@extends('master')

@section('title', 'Virtual Wallet')

@section('content')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <router-link class="navbar-brand" to="/">Welcome</router-link>
    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <router-link class="nav-link" to="/" >Profile</router-link>
            </li>

        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item">
                <router-link  class="nav-link" to="/login" >Login</router-link>
            </li>
            <li>
                <router-link  class="nav-link" to="/register" >Register</router-link>
            </li>
        </ul>
    </div>
</nav>


{{-- <router-link to="/">Welcome</router-link>
<router-link to="/register">Register</router-link>
<router-link to="/login">Login</router-link> --}}

<router-view style="padding:20px;"></router-view>

@endsection
@section('pagescript')
<script src="js/app.js"></script>
 @stop
