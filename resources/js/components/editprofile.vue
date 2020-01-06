<template>
  <div>
    <!-- <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>-->
    <div class="row">
      <div class="col-md-2">
        <div class="list-group">
          <router-link
            class="nav-link list-group-item list-group-item-action active"
            to="/profile/edit"
            v-if="this.$store.state.token"
          >Edit Profile</router-link>
          <router-link
            class="nav-link list-group-item list-group-item-action"
            to="/profile"
            v-if="this.$store.state.token"
          >Profile</router-link>
        </div>
      </div>
      <div class="col-md-10">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <h4>Edit Profile</h4>
                <hr />
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form>
                  <div>
                    <div>
                      <label for="name">Name</label>
                      <input
                        v-model="name"
                        name="name"
                        type="text"
                        id="name"
                        class="form-control"
                        placeholder="Name"
                      />
                    </div>

                    <div>
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

                    <div v-if="this.$store.state.user.type == 'u'">
                      <label for="nif">Nif</label>
                      <input
                        v-model="nif"
                        min="100000000"
                        max="999999999"
                        minlength="9"
                        maxlength="9"
                        type="number"
                        id="nif"
                        class="form-control"
                        placeholder="000000000"
                      />
                    </div>

                    <div>
                      <label for="passwordCurrent">Current Password</label>
                      <input
                        v-model="currentPassword"
                        type="password"
                        id="passwordCurrent"
                        class="form-control"
                        autocomplete="off"
                      />
                    </div>

                    <div>
                      <label for="password">Password</label>
                      <input
                        v-model="password"
                        type="password"
                        id="password"
                        class="form-control"
                        autocomplete="off"
                      />
                    </div>

                    <div>
                      <label for="passwordConfirmation">Password confirmation</label>
                      <input
                        v-model="passwordConfirmation"
                        type="password"
                        id="passwordConfirmation"
                        class="form-control"
                        autocomplete="off"
                      />
                    </div>
                  </div>
                  <!-- end form group -->
                  <br />
                  <div v-if="error" class="alert alert-danger" role="alert">{{error}}</div>
                  <br />
                  <div class="text-center">
                    <button
                      type="button"
                      class="btn btn-primary"
                      v-on:click.prevent="submit()"
                    >Save changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
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
      photo: this.$store.state.user.name || "",
      nif: this.$store.state.user.nif || "",
      password: "",
      passwordConfirmation: "",
      currentPassword: "",
      error: null
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
      this.error = null;
      axios
        .post("api/users/me/edit", formData)
        .then(response => {
          this.$store.commit("setUser", response.data);
          this.$router.push("/profile");
        })
        .catch(err => {
          this.error = err.response.data.message;
          console.log(err.response.data);
        });
    }
  },
  sockets: {
    notificationFromServer(msg) {
      Vue.$toast.open(msg);
    }
  }
};
</script>

<style>
/* .active {
  background-color: black !important;
} */
</style>
