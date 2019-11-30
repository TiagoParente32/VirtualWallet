<template>
  <div>
    <h1>Balance</h1>
    <h2>{{balance}} €</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Transfer e-mail</th>
          <th>Type Payment</th>
          <th>category</th>
          <th>date</th>
          <th>start balance</th>
          <th>end balance</th>
          <th>value</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="movement in movements" :key="movement.id">
          <td>{{ movement.id }}</td>
          <td v-if="movement.type == 'e'">Expense</td>
          <td v-else-if="movement.type == 'i'">Income</td>

          <td v-if="movement.transfer_wallet == null">NA</td>
          <td v-else>{{movement.transfer_wallet.email}}</td>
          <!-- <td>{{ movement.transfer_wallet.user.photo}}</td>  para ir buscar a foto do user da outra wallet-->
          <div v-if="movement.transfer == 1">
            <td v-if="movement.type_payment == 'c' ">Cash</td>
            <td v-else-if="movement.type_payment == 'bt'">Bank Transfer</td>
            <td v-else-if="movement.type_payment == 'mb'">MB Payment</td>
          </div>
          <div v-else>
            <td>Not A Transfer</td>
          </div>
          <td>{{ movement.category.name}}</td>
          <td>{{ movement.date }}</td>
          <td>{{ movement.start_balance }}</td>
          <td>{{ movement.end_balance }}</td>
          <td>{{ movement.value }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      balance: "",
      movements: ""
    };
  },
  methods: {
    getWallet() {
      axios
        .get("api/users/me/wallet")
        .then(response => {
          //console.log(response);
          this.balance = response.data.balance;
          return axios.get("api/users/me/wallet/movements");
        })
        .then(response => {
          console.log(response);
          this.movements = response.data.data;
        });
    }
  },
  mounted() {
    this.getWallet();
  }
};
</script>

<style>
</style>
