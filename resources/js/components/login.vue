<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input
        type="email"
        id="inputEmail"
        class="form-control"
        required
        v-model="userData.email"
        @keypress.enter="setFocus()"
      />
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input
        type="password"
        id="inputPassword"
        class="form-control"
        required
        ref="password"
        v-model="userData.password"
        @keypress.enter="login()"
      />
    </div>
    <div class="form-group">
      <a class="btn btn-primary" v-on:click.prevent="login">Login</a>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      title: "Login",
      userData: {
        email: null,
        password: null
      }
    };
  },
  methods: {
    login() {
      axios
        .post("api/login", {
          email: this.userData.email,
          password: this.userData.password
        })
        .then(response => {
          console.log(response.data.access_token);
          //localStorage.setItem("user-token", response.data.access_token);
        })
        .catch(err => {
          //localStorage.removeItem("user-token"); // if the request fails, remove any possible user token if possible
          reject(err);
        });
    },
    setFocus() {
      this.$refs.password.focus();
    }
  }
};
</script>

<style>
</style>
