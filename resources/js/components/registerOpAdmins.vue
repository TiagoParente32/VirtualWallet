<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>
    <form>
      <div>
        <div>
          <label for="type">Type of User</label>
          <select class="form-control" id="type" name="type" v-model="userData.type">
            <option
              v-for="option in optionsTypes"
              :key="option.value"
              v-bind:value="option.value"
            >{{ option.text }}</option>
          </select>
        </div>
        <div>
          <label for="name">Name</label>
          <input
            type="text"
            id="name"
            class="form-control"
            placeholder="Name"
            required
            v-model="userData.name"
          />
        </div>
        <div>
          <label for="email">Email</label>
          <input
            type="text"
            id="email"
            class="form-control"
            placeholder="Email"
            required
            v-model="userData.email"
          />
        </div>
      </div>
      <br />
      <div class="form-row">
        <div class="col-6">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            class="form-control"
            required
            placeholder="Password"
            v-model="userData.password"
            aria-describedby="passwordHelpInline"
          />
          <small
            id="passwordHelpInline"
            class="text text-muted"
          >Your password must be 3+ characters long</small>
        </div>
      </div>
      <br />
      <div class="form-row">
        <div class="col">
          <label for="photo">Photo</label>
          <div class="custom-file">
            <label id="photoLabel" class="custom-file-label" for="photo">Choose Photo</label>
            <input
              class="custom-file-input"
              type="file"
              name="photo"
              id="photo"
              accept="image/*"
              @change="onFileSelected"
            />
          </div>
        </div>
      </div>
      <br />
      <div id="error" class="alert alert-danger" role="alert" hidden>Error Message Here</div>
      <br />
      <div class="form-group">
        <button type="button" class="btn btn-primary" v-on:click.prevent="register">Register</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      title: "Register",
      userData: {
        type: "",
        name: "",
        email: "",
        password: ""
      },
      photo: "",
      optionsTypes: [
        { text: "Administrator", value: "a" },
        { text: "Operator", value: "o" }
      ]
    };
  },
  methods: {
    onFileSelected(event) {
      this.photo = event.target.files[0];
      if (this.photo === undefined) {
        return;
      }
      var filename = this.photo.name;
      var element = document.getElementById("photoLabel");
      element.innerHTML = filename;
    },
    register() {
      //console.log(this.userData);

      let formData = new FormData();
      formData.append("photo", this.photo);
      formData.append("name", this.userData.name);
      formData.append("type", this.userData.type);
      formData.append("email", this.userData.email);
      formData.append("password", this.userData.password);

      axios
        .post("api/users/create", formData)
        .then(response => {
          this.$router.push("/profile");
        })
        .catch(err => {
          //console.log(err.response.data);
          document.querySelector("#error").hidden = false;
          document.querySelector("#error").innerHTML =
            err.response.data.message;
        });
    }
  }
};
</script>

<style>
</style>
