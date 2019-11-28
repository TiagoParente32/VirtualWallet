<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>

    <div class="form-group">
      <label for="name">Name</label>
      <input
        type="text"
        id="name"
        class="form-control"
        placeholder="Your Name"
        required
        v-model="userData.name"
        @keypress.enter="setFocusEmail()"
      />
      <label for="email">Email</label>
      <input
        type="text"
        id="email"
        class="form-control"
        placeholder="Your Email"
        required
        v-model="userData.email"
        ref="email"
        @keypress.enter="setFocusPassword()"
      />
      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        class="form-control"
        required
        v-model.lazy="userData.password"
        ref="password"
        @keypress.enter="setFocusNif()"
      />
      <label for="nif">Nif</label>
      <input
        min="000000001"
        max="999999999"
        minlength="9"
        maxlength="9"
        type="number"
        id="nif"
        class="form-control"
        required
        placeholder="000000000"
        v-model="userData.nif"
        ref="nif"
        @keypress.enter="setFocusPhoto()"
      />
      <br />
      <label for="photo">Photo</label>
      <input
        type="file"
        name="photo"
        id="photo"
        accept="image/*"
        @change="onFileSelected"
        ref="photo"
        @keypress.enter="register()"
      />
      <div class="form-group">
        <a class="btn btn-primary" v-on:click.prevent="register">Register</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      title: "Register",
      userData: {
        name: null,
        email: null,
        password: null,
        nif: null
      },
      photo: null
    };
  },
  methods: {
    onFileSelected(event) {
      this.photo = event.target.files[0];
      if (this.photo === undefined) {
        return;
      }
    },
    register() {
      //AQUI METER ERROS :)
      //   if (!this.userData.email || !this.userData.password) {
      //     document.querySelector("#error").hidden = false;
      //     return;
      //   }
      console.log(this.userData);

      let formData = new FormData();
      formData.append("photo", this.photo);
      formData.append("name", this.userData.name);
      formData.append("email", this.userData.email);
      formData.append("password", this.userData.password);
      formData.append("nif", this.userData.nif);
      axios
        .post("api/register", formData)
        .then(response => {
          this.$router.push("/login");
        })
        .catch(err => {
          console.log(err.response.data);
        });
    },
    setFocusEmail() {
      this.$refs.email.focus();
    },
    setFocusPassword() {
      this.$refs.password.focus();
    },
    setFocusNif() {
      this.$refs.nif.focus();
    },
    setFocusPhoto() {
      this.$refs.photo.focus();
    }
  }
};
</script>

<style>
</style>
