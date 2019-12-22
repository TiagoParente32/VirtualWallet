<template>
  <div>
    <h1>Balance</h1>
    <h2>{{balance}} €</h2>
    <router-link to="/users/me/wallet/statistics">wallet stats</router-link>

    <div>
      <div class="row">
        <div class="col">
          <label for="id">ID:</label>
          <input type="text" id="id" class="form-control" v-model="filterData.id" />
        </div>

        <div class="col">
          <label for="type">Type:</label>
          <select class="form-control" id="type" name="type" v-model="filterData.type">
            <option
              v-for="option in optionsType"
              :key="option.value"
              v-bind:value="option.value"
            >{{ option.text }}</option>
          </select>
        </div>

        <div class="col">
          <label for="dataMin">From:</label>
          <input type="date" id="dateMin" class="form-control" v-model="filterData.dataMin" />
        </div>

        <div class="col">
          <label for="dataMin">Until:</label>
          <input type="date" id="dateMin" class="form-control" v-model="filterData.dataMax" />
        </div>

        <div class="col">
          <label for="type_payment">Type of Payment:</label>
          <select
            class="form-control"
            id="type_payment"
            name="type_payment"
            v-model="filterData.type_payment"
          >
            <option
              v-for="option in optionsTypePayment"
              :key="option.value"
              v-bind:value="option.value"
            >{{ option.text }}</option>
          </select>
        </div>

        <div class="col">
          <label for="transfer">Transfer:</label>
          <select class="form-control" id="transfer" name="transfer" v-model="filterData.transfer">
            <option
              v-for="option in optionsTransfer"
              :key="option.value"
              v-bind:value="option.value"
            >{{ option.text }}</option>
          </select>
        </div>

        <div v-if="filterData.transfer">
          <div class="col">
            <label for="transfer_email">Transfer e-mail:</label>
            <input
              type="text"
              id="transfer_email"
              class="form-control"
              v-model="filterData.transfer_email"
            />
          </div>
        </div>
      </div>
      <br />
      <button type="submit" class="btn btn-primary" v-on:click.prevent="filter">Submit</button>
      <button type="button" class="btn btn-link" v-on:click.prevent="clear">Clear all</button>
    </div>
    <br />

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
    <!-- <wallet-chart v-if="this.loaded" :chartdata="movements" :options="options" /> -->
  </div>
</template>

<script>
import WalletList from "./walletList";
import MovementEdit from "./walletEdit";
import WalletStats from "./walletStats";
export default {
  data() {
    return {
      loaded: false,
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
      },
      options: {
        responsive: true
      },
      optionsType: [
        { text: "All", value: null },
        { text: "Income", value: "i" },
        { text: "Expense", value: "e" }
      ],
      optionsTypePayment: [
        { text: "All", value: null },
        { text: "Cash", value: "c" },
        { text: "Bank transfer", value: "bt" },
        { text: "MB payment", value: "mb" }
      ],
      optionsTransfer: [
        { text: "N/A", value: null },
        { text: "Sim", value: 1 },
        { text: "Não", value: 0 }
      ],
      filterData: {
        id: null,
        type: null,
        dataMin: null,
        dataMax: null,
        type_payment: null,
        transfer: null,
        transfer_email: null
      }
    };
  },
  methods: {
    getWallet(movementsPageNr = 1) {
      axios.get("api/users/me/wallet").then(response => {
        //console.log(response);
        this.balance = response.data.balance;
        axios
          .get(`api/users/me/wallet/movements?page=${movementsPageNr}`)
          .then(response => {
            //console.log(response);
            this.movements = response.data.data;
            this.movementsPagination = response.data.meta;
          });
      });

      axios
        .get("api/users/me/wallet")
        .then(response => {
          this.balance = response.data.balance;
          return axios.post(
            `/api/users/me/wallet/movements/filter?page=${movementsPageNr}`,
            this.filterData
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
    },
    filter(movementsPageNr = 1) {
      //console.log(this.filterData);
      axios
        .post(
          `/api/users/me/wallet/movements/filter?page=${movementsPageNr}`,
          this.filterData
        )
        .then(response => {
          //console.log(response);
          this.movements = response.data.data;
          this.movementsPagination = response.data.meta;
        });
    },
    clear: function() {
      this.filterData.id = null;
      this.filterData.type = null;
      this.filterData.dataMin = null;
      this.filterData.dataMax = null;
      this.filterData.type_payment = null;
      this.filterData.transfer = null;
      this.filterData.transfer_email = null;
      this.filter();
    }
  },
  mounted() {
    // this.loaded = false;
    // this.getWallet();
    // this.loaded = true;
    //this.getWallet();
    this.filter();
  },
  components: {
    "wallet-list": WalletList,
    "movement-edit": MovementEdit,
    "wallet-chart": WalletStats
  }
};
</script>

<style>
</style>
