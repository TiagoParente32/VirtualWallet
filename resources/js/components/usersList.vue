<template>
  <div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Photo</th>
          <th>Name</th>
          <th>Email</th>
          <th>Type</th>
          <th>Active</th>
          <th>Balance</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td v-if="user.photo !== null">
            <img
              :src="`./storage/fotos/${user.photo}`"
              class="img-thumbnail"
              height="200"
              width="200"
            />
          </td>
          <td v-else>
            <img
              :src="`./storage/fotos/default.png`"
              class="img-thumbnail"
              height="200"
              width="200"
            />
          </td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td v-if="user.type == 'a'">Administator</td>
          <td v-else-if="user.type == 'o'">Operator</td>
          <td v-else>User</td>
          <td v-if="user.active">Active</td>
          <td v-else>Inactive</td>
          <td v-if="user.balance">Has Money</td>
          <td v-else>Empty</td>

          <td>
            <button
              v-if="user.type == 'a' || user.type == 'o'"
              class="btn btn-sm btn-danger"
              v-on:click.prevent="deleteUser(user.id)"
            >Delete</button>
            <button
              v-else-if="user.type == 'u' && user.active"
              class="btn btn-sm btn-danger"
              v-on:click.prevent="activateUser(user.id)"
            >Deactivate</button>
            <button
              v-else-if="user.type == 'u' && !user.active"
              class="btn btn-sm btn-success"
              v-on:click.prevent="activateUser(user.id)"
            >Activate</button>
          </td>
        </tr>
      </tbody>
    </table>
    <br />
    <paginate
      class="d-flex justify-content-center"
      v-model="usersPage"
      :page-count="usersPagination !== null && usersPagination.total !== 0 ? usersPagination.last_page : 0"
      :click-handler="this.getUsers"
      :margin-pages="2"
      :page-range="5"
      :container-class="'pagination'"
      :page-class="'page-item'"
      :page-link-class="'page-link'"
      :prev-class="'page-item'"
      :prev-link-class="'page-link'"
      :next-class="'page-item'"
      :next-link-class="'page-link'"
    ></paginate>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      usersPagination: null,
      usersPage: null
    };
  },
  methods: {
    getUsers(usersPageNr = 1) {
      axios.get(`api/users?page=${usersPageNr}`).then(response => {
        this.users = response.data.data;
        this.usersPagination = response.data.meta;
      });
    },
    deleteUser(id) {
      axios.delete(`api/users/${id}`).then(response => {
        this.getUsers(this.usersPage);
      });
    },
    activateUser(id) {
      axios.patch(`api/users/${id}`).then(response => {
        this.getUsers(this.usersPage);
      });
    }
  },
  mounted() {
    this.getUsers();
  }
};
</script>

<style >
img {
  height: 70px;
  width: 70px;
}
</style>
