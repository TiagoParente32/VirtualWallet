<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>
    <form>
      <div class="form-group col-auto">
        <label for="email">Email</label>
        <input type="email" id="inputEmail" class="form-control" required v-model="userData.email" />
      </div>
      <div class="form-group col-auto">
        <label for="password">Password</label>
        <input
          type="password"
          id="inputPassword"
          class="form-control"
          required
          ref="password"
          v-model="userData.password"
        />
      </div>
      <div
        id="error"
        class="alert alert-danger"
        role="alert"
        hidden
      >Please type your credentials to login</div>
      <div class="form-group col-auto">
        <button type="button" class="btn btn-primary" v-on:click.prevent="login">Login</button>
      </div>
    </form>
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
        .post("api/login", this.userData)
        .then(response => {
          //console.log(response.data.access_token);
          this.$store.commit("setToken", response.data.access_token);
          return axios.get("api/users/me");
        })
        .then(response => {
          //console.log(response.data);
          this.$store.commit("setUser", response.data);
          this.$router.push("/");
        })
        .catch(err => {
          this.$store.commit("clearUserAndToken");
          //console.log(err.response.data.msg);
          document.querySelector("#error").hidden = false;
          document.querySelector("#error").innerHTML = err.response.data.msg;
        });
    }
  }
};
</script>

<style>
</style>
