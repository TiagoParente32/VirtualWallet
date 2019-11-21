<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="text" id="email" class="form-control" required />
      <label for="password">Password</label>
      <input type="password" id="password" class="form-control" required />
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      title: "Login"
    };
  }
};
</script>

<style>
</style>
