<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>

    <div>
      <p class="lead">{{message}}</p>
      <p class="h6">Number of Wallets : {{ walletcount }}</p>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      title: "Welcome",
      message:
        "We have waited so long to have yo u among us. At last, the time has come. We are most delightfully welcoming you to join us today!",
      walletcount: undefined
    };
  },
  methods: {
    getWalletCount() {
      axios.get("api/walletcount").then(response => {
        this.walletcount = response.data.walletcount;
      });
    }
  },
  mounted() {
    this.getWalletCount();
  }
};
</script>

<style>
.jumbotron {
  background-color: #343a40;
}
.jumbotron h1 {
  color: #fff;
}

.jumbotron h2 {
  color: #fff;
}
.jumbotron label {
  color: #fff;
}
/*
html {
  overflow: scroll;
}
::-webkit-scrollbar {
  width: 0px;
  background: transparent; // make scrollbar transparent
} */
</style>

