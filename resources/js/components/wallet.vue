<template>
  <div>
    <h1>Balance</h1>
    <h2>{{balance}} €</h2>

    <wallet-list
      v-bind:movements="movements"
      :opened="opened"
      @toggle="toggle"
      @edit-movement="editMovement"
    ></wallet-list>

    <div class="alert alert-success" v-if="showSuccess">
      <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
      <strong>{{ successMessage }}</strong>
    </div>

    <movement-edit
      v-if="editingMovement"
      v-bind:currentMovement="currentMovement"
      @save-movement="saveMovement"
      @cancel-edit="cancelEdit"
    ></movement-edit>
    <br />
    <paginate
      class="d-flex justify-content-center"
      v-model="movementsPage"
      :page-count="movementsPagination !== null && movementsPagination.total !== 0 ? movementsPagination.last_page : 0"
      :click-handler="this.getWallet"
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
import WalletList from "./walletList";
import MovementEdit from "./walletEdit";
export default {
  data() {
    return {
      opened: [],
      balance: "",
      movements: [],
      movementsPagination: null,
      movementsPage: null,
      editingMovement: false,
      showSuccess: false,
      showFailure: false,
      successMessage: "",
      failMessage: "",
      currentMovement: null,
      put: {
        category: null,
        description: null
      }
    };
  },
  methods: {
    getWallet(movementsPageNr = 1) {
      axios
        .get("api/users/me/wallet")
        .then(response => {
          //console.log(response);
          this.balance = response.data.balance;
          return axios.get(
            `api/users/me/wallet/movements?page=${movementsPageNr}`
          );
        })
        .then(response => {
          //console.log(response);
          this.movements = response.data.data;
          this.movementsPagination = response.data.meta;
        });
    },
    details() {},
    toggle(id) {
      const index = this.opened.indexOf(id);
      if (index > -1) {
        this.opened.splice(index, 1);
      } else {
        this.opened.push(id);
      }
    },
    editMovement: function(movement) {
      this.currentMovement = Object.assign({}, movement);
      if (!this.currentMovement.category) {
        this.currentMovement.category = {};
      }
      this.editingMovement = true;
      this.showSuccess = false;
      //     var elmnt = document.getElementById("edit");
      //     console.log(elmnt);
      //   elmnt.scrollIntoView();
    },
    saveMovement: function(movement) {
      this.editingMovement = false;
      console.log(movement);
      this.put.category = movement.category.id;
      this.put.description = movement.description;
      //console.log(this.put);
      axios.put("api/movements/" + movement.id, this.put).then(response => {
        this.showSuccess = true;
        this.successMessage = "Movement Updated";
        // Copies response.data.data properties to this.currentMovement
        // without changing this.currentMovement reference
        console.log(response.data);
        Object.assign(this.currentMovement, response.data.data);
        Object.assign(
          this.movements.find(m => m.id == response.data.data.id),
          response.data.data
        );
        this.currentMovement = null;
        this.editingMovement = false;
      });
    },
    cancelEdit: function() {
      this.showSuccess = false;
      this.editingMovement = false;
    }
  },
  mounted() {
    this.getWallet();
  },
  components: {
    "wallet-list": WalletList,
    "movement-edit": MovementEdit
  }
};
</script>

<style>
</style>
