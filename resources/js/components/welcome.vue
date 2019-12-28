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
    /*
    console.log("a enviar mail"); 
    let emailData = {"subject": "asd", "to": "pedro-santos96@live.com.pt", "text": "Check out you virtual wallet, you have a new movement!"};
    console.log(emailData);
    axios.post("api/sendemail", emailData).then(response => {
      console.log("Email enviado!");
      console.log(response.data);
    });
  */
    this.getWalletCount();
    this.$socket.emit("sendSocketEmailToServer", this.$store.state.user.email);
  },
  connect() {
    console.log("socket connected (socketID = " + this.$socket.id + ")");
  },
  sockets: {
    sendEmail(email) {
      console.log("a enviar mail"); 
      var emailData = {"subject": "Check out your wallet", "to": email, "text": "Check out you virtual wallet, you have a new movement!"}; 
      axios.post("api/sendemail", emailData).then(response => {
          console.log(response.data);
      });
    },
    sockets: {
      notificationFromServer(msg) {
        console.log(msg);
      }
    }
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

