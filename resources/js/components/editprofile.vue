<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>

    <div class="form-group">
      <label for="name">Name</label>
      <input
        v-model="name"
        name="name"
        type="text"
        id="name"
        class="form-control"
        placeholder="Your Name"
        @keypress.enter="setFocusPhoto()"
      />
      <br />
      <label for="photo">Photo</label>
      <br />
      <input
        type="file"
        name="photo"
        id="photo"
        accept="image/*"
        @change="onFileSelected"
        ref="photo"
        @keypress.enter="setFocusNif()"
      />
      <br />
      <br />
      <label for="nif">Nif</label>
      <input
        v-model="nif"
        min="000000001"
        max="999999999"
        minlength="9"
        maxlength="9"
        type="number"
        id="nif"
        class="form-control"
        placeholder="000000000"
        ref="nif"
        @keypress.enter="setFocusPassword()"
      />
      <label for="passwordConfirmation">Current Password</label>
      <input v-model="currentPassword" type="password" id="password" class="form-control" />

      <label for="password">Password</label>
      <input v-model="password" type="password" id="password" class="form-control" />

      <label for="passwordConfirmation">Password confirmation</label>
      <input v-model="passwordConfirmation" type="password" id="password" class="form-control" />

      <br />
      <div class="form-group">
        <a class="btn btn-primary" v-on:click.prevent="submit()">Save changes</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      title: "Edit Profile",
      name: this.$store.state.user.name || "",
      photo: "",
      nif: this.$store.state.user.nif || "",
      password: "",
      passwordConfirmation: "",
      currentPassword: ""
    };
  },
  methods: {
    onFileSelected(event) {
      this.photo = event.target.files[0];
      if (this.photo === undefined) {
        return;
      }
    },
    submit() {
      //tou a mostrar o erro no backend , mas podes meter aqui um erro tambem
      //   if (this.password != this.passwordConfirmation) {
      //     return;
      //   }

      let formData = new FormData();
      formData.append("photo", this.photo);
      formData.append("name", this.name);
      formData.append("password", this.password);
      formData.append("passwordConfirmation", this.passwordConfirmation);
      formData.append("currentPassword", this.currentPassword);
      formData.append("nif", this.nif);
      formData.append("_method", "PUT");

      //console.log(user);
      //console.log(formData.get("photo"));
      axios
        .post("api/me/edit", formData)
        .then(response => {
          this.$store.commit("setUser", response.data);
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
