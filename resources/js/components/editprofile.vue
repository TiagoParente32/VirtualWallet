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
      <label for="photo">Photo</label><br>
      <input
        type="file"
        name="photo"
        id="photo"
        accept="image/*"
        ref="photo"
        @keypress.enter="setFocusNif()"
      /> 
      <br><br>
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
      />
      <label for="passwordConfirmation">Password confirmation</label>
      <input
        v-model="passwordConfirmation"
        type="password"
        id="password"
        class="form-control"
      />
      <br>
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
      name: this.$store.state.user.name,
      photo: null,
      nif: this.$store.state.user.nif,
      password: null,
      passwordConfirmation: null
    };
  },
  methods: {
    submit(){
      if(this.password != this.passwordConfirmation){
        return;
      }
      
      let user = this.$store.state.user;
      user.name =  this.name;
      user.photo = this.photo;
      user.nif = this.nif;
      user.password = this.password;
      console.log(user);

      axios.put('/api/me/edit', user)
      .then(this.$store.commit("setUser", user));
      
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
