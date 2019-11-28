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
    <div
      id="error"
      class="alert alert-danger"
      role="alert"
      hidden
    >Please type your credentials to login</div>
    <div
      id="wrongCredentials"
      class="alert alert-danger"
      role="alert"
      hidden
    >Wrong credentials</div>
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
      if (!this.userData.email || !this.userData.password) {
        document.querySelector("#error").hidden = false;
        return;
      }
      document.querySelector("#error").hidden = true;
      axios
        .post("api/login", {
          email: this.userData.email,
          password: this.userData.password
        })
        .then(response => {
          //console.log(response.data.access_token);
          this.$store.commit("setToken", response.data.access_token);
          return axios.get("api/users/me");
        })
        .then(response => {
          console.log(response.data);
          this.$store.commit("setUser", response.data);
          this.$router.push("/");
        })
        .catch(err => {
          document.querySelector("#wrongCredentials").hidden = false;
          this.$store.commit("clearUserAndToken");
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
