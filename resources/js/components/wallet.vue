<template>
  <div>
    <h1>Balance</h1>
    <h2>{{balance}} €</h2>
    <table class="table table-bordered table-hover">
      <thead class="thead-dark" align="center">
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Transfer e-mail</th>
          <th>Type Payment</th>
          <th>Category</th>
          <th>Date</th>
          <th>Value (€)</th>
          <th>Start Balance (€)</th>
          <th>End Balance (€)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="movement in movements">
          <tr
            :key="movement.id"
            v-bind:class="{'table-success': (movement.type == 'i'), 'table-danger' : (movement.type == 'e'), opened: opened.includes(movement.id)}"
            align="center"
            @click="toggle(movement.id)"
          >
            <td>{{ movement.id }}</td>
            <td v-if="movement.type == 'e'">Expense</td>
            <td v-else-if="movement.type == 'i'">Income</td>

            <td v-if="movement.transfer_wallet == null">NA</td>
            <td v-else>{{movement.transfer_wallet.email}}</td>
            <!-- <td>{{ movement.transfer_wallet.user.photo}}</td>  para ir buscar a foto do user da outra wallet-->
            <template v-if="movement.transfer == 0">
              <td v-if="movement.type_payment == 'c'">Cash</td>
              <td v-else-if="movement.type_payment == 'bt'">Bank Transfer</td>
              <td v-else-if="movement.type_payment == 'mb'">MB Payment</td>
            </template>
            <template v-else>
              <td>Transfer</td>
            </template>
            <td v-if="movement.category == null">NA</td>
            <td v-else>{{movement.category.name}}</td>
            <td>{{ movement.date }}</td>
            <td>{{ movement.value }}€</td>
            <td>{{ movement.start_balance }}€</td>
            <td>{{ movement.end_balance }}€</td>
            <td>
              <button type="button" class="btn btn-primary btn-sm">Details</button>
            </td>
          </tr>
          <div :key="movement.id + '-details'" v-if="opened.includes(movement.id)">
            <div v-if="movement.transfer_wallet != null">
              <div v-if="movement.transfer_wallet.user.photo !== null">
                <img
                  :src="`./storage/fotos/${movement.transfer_wallet.user.photo}`"
                  class="img-thumbnail"
                  height="200"
                  width="200"
                />
              </div>
              <div v-else>
                <img
                  :src="'./storage/fotos/default.png'"
                  class="img-thumbnail"
                  height="200"
                  width="200"
                />
              </div>
            </div>

            <p>Description: {{movement.description}}</p>
            <p>Source Description: {{movement.source_description}}</p>
            <p>IBAN: {{movement.iban}}</p>
            <p>MB Entity Code: {{movement.mb_entity_code}}</p>
            <p>MB Payment Reference{{movement.mb_payment_reference}}</p>
          </div>
        </template>
      </tbody>
    </table>
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
export default {
  data() {
    return {
      opened: [],
      balance: "",
      movements: [],
      movementsPagination: null,
      movementsPage: null
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
          console.log(response);
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
    }
  },
  mounted() {
    this.getWallet();
  }
};
</script>

<style>
</style>
