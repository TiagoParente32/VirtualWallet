<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>

    <div>
      <p class="lead">{{message}}</p>
      <p class="h6">Number of Wallets : {{walletCount}}</p>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      title: "Welcome",
      message:
        "We have waited so long to have you among us. At last, the time has come. We are most delightfully welcoming you to join us today!",
      walletCount: 300
    };
  }
};
</script>

<style>
.jumbotron {
  background-color: lightgray;
}
</style>

