<template>
</template>
<script type="text/javascript">
export default {
  sockets: {
    sendEmail(email) {
        emailData.subject = "Check out your wallet";
        emailData.to = email;
        emailData.text = "Check out you virtual wallet, you have a new movement!";
        axios.get("api/sendemail", emailData).then(response => {
            console.log("Email enviado!");
        });
    }
  }
};
</script>
