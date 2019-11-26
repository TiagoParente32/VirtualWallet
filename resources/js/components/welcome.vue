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
      currentUser: null,
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
        
    axios.get('/api/authuser')
      .then(response=>{this.currentUser = response.data.data});

    this.getWalletCount();
  }
};
</script>

<style>
.jumbotron {
  background-color: lightgray;
}

html {
  overflow: scroll;
}
::-webkit-scrollbar {
  width: 0px;
  background: transparent; /* make scrollbar transparent */
}
</style>

