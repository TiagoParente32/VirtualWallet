<template>
  <div>
    <!-- <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>-->
    <!-- <div>
      <div>
        <div v-if="this.$store.state.user.photo !== null">
          <img :src="`./storage/fotos/${this.$store.state.user.photo}`" style="border-radius: 50%" />
        </div>
        <div v-else>
          <img :src="`./storage/fotos/default.png`" style="border-radius: 50%" />
        </div>
        <h2>{{this.$store.state.user.name}}</h2>
        <hr />
      </div>
      <div>
        <div>
          <label for="email">Email</label>
          <input
            type="text"
            id="email"
            class="form-control"
            v-model="this.$store.state.user.email"
            readonly
          />
        </div>

        <div>
          <label for="nif">Nif</label>
          <input
            min="000000001"
            max="999999999"
            minlength="9"
            maxlength="9"
            type="text"
            id="nif"
            class="form-control"
            v-model="this.$store.state.user.nif"
            readonly
          />
        </div>

        <div>
          <label for="type">Type</label>
          <div v-if="this.$store.state.user.type === 'u'">
            <input type="text" id="type" class="form-control" value="User" readonly />
          </div>
          <div v-else-if="this.$store.state.user.type === 'o'">
            <input type="text" id="type" class="form-control" value="Operator" readonly />
          </div>
          <div v-else-if="this.$store.state.user.type === 'a'">
            <input type="text" id="type" class="form-control" value="Admin" readonly />
          </div>
        </div>
      </div>
    </div>-->

    <div class="row">
      <div class="col-md-2">
        <div class="list-group">
          <router-link
            class="nav-link list-group-item list-group-item-action"
            to="/profile/edit"
            v-if="this.$store.state.token"
          >Edit Profile</router-link>
          <router-link
            class="nav-link list-group-item list-group-item-action active"
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
                <h4>{{this.$store.state.user.name}} Profile</h4>
                <div>
                  <div v-if="this.$store.state.user.photo !== null">
                    <img
                      :src="`./storage/fotos/${this.$store.state.user.photo}`"
                      style="border-radius: 50%"
                    />
                  </div>
                  <div v-else>
                    <img :src="`./storage/fotos/default.png`" style="border-radius: 50%" />
                  </div>
                </div>
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
                        v-model="this.$store.state.user.name"
                        name="name"
                        type="text"
                        id="name"
                        class="form-control"
                        placeholder="Name"
                        readonly
                      />
                    </div>

                    <div>
                      <label for="name">Email</label>
                      <input
                        v-model="this.$store.state.user.email"
                        name="name"
                        type="text"
                        id="name"
                        class="form-control"
                        placeholder="Name"
                        readonly
                      />
                    </div>

                    <div v-if="this.$store.state.user.type == 'u'">
                      <label for="nif">Nif</label>
                      <input
                        v-model="this.$store.state.user.nif"
                        min="100000000"
                        max="999999999"
                        minlength="9"
                        maxlength="9"
                        type="number"
                        id="nif"
                        class="form-control"
                        placeholder="000000000"
                        readonly
                      />
                    </div>
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
  data() {
    return {
      title: "Profile"
    };
  },
  sockets: {
    notificationFromServer(msg) {
      Vue.$toast.open(msg);
    }
  }
};
</script>

<style >
</style>
