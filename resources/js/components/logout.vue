<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>
    <div class="form-group col-auto">
      <button type="button" class="btn btn-primary" v-on:click.prevent="logout">Logout</button>
    </div>
  </div>
</template>

<script type="text/javascript">
export default {
  data() {
    return {
      title: "Logout Confirmation"
    };
  },
  methods: {
    logout() {
      axios
        .post("api/logout")
        .then(response => {
          this.$store.commit("clearUserAndToken");
          this.$router.push("/");
        })
        .catch(error => {
          console.log(error);
          this.$router.push("/");
        });
    }
  }
};
</script>
