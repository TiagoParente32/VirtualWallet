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
      <br>
      <label for="photo">Photo</label>
      <input
        type="file"
        name="photo"
        id="photo"
        accept="image/*"
        ref="photo"
        @keypress.enter="setFocusNif()"
      /> 
      <br>
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
      <label for="password">Password</label>
      <input
        v-model="password"
        type="password"
        id="password"
        class="form-control"
        ref="password"
      />
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
      name: null,
      photo: null,
      nif: null,
      password: null
    };
  },
  methods: {
    submit(){
      let user = {
        name: this.name,
        photo: this.photo,
        nif: this.nif,
        passsword: this.password
      }
       axios.put('/api/me/edit', user)
        .then();
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
