@extends('master')

@section('title', 'Vue.js App')

@section('content')

<router-link to="/">Welcome</router-link>
<router-link to="/register">Register</router-link>
<router-link to="/login">Login</router-link>

<router-view></router-view>

@endsection
@section('pagescript')
<script src="js/app.js"></script>
 @stop
