<template>
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
          v-bind:class="{'table-success': (movement.type == 'i'), 'table-danger' : (movement.type == 'e'), active : currentMovement === movement}"
          align="center"
        >
          <td>{{ movement.id }}</td>
          <td v-if="movement.type == 'e'">Expense</td>
          <td v-else-if="movement.type == 'i'">Income</td>
          <td v-if="movement.transfer_wallet == null">-</td>
          <td v-else>{{movement.transfer_wallet.email}}</td>
          <template v-if="movement.transfer == 0">
            <td v-if="movement.type_payment == 'c'">Cash</td>
            <td v-else-if="movement.type_payment == 'bt'">Bank Transfer</td>
            <td v-else-if="movement.type_payment == 'mb'">MB Payment</td>
          </template>
          <template v-else>
            <td>Transfer</td>
          </template>
          <td v-if="movement.category == null">-</td>
          <td v-else>{{movement.category.name}}</td>
          <td>{{ movement.date }}</td>
          <td>{{ movement.value }}€</td>
          <td>{{ movement.start_balance }}€</td>
          <td>{{ movement.end_balance }}€</td>
          <td>
            <button
              type="button"
              class="btn btn-primary btn-sm"
              @click.prevent="detailsMovement(movement)"
            >Details</button>

            <button
              type="button"
              class="btn btn-primary btn-sm"
              @click.prevent="editMovement(movement)"
            >Edit</button>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["movements", "currentMovement"],
  methods: {
    detailsMovement(movement) {
      this.$emit("details-movement", movement);
    },
    editMovement(movement) {
      this.$emit("edit-movement", movement);
    }
  }
};
</script>

<style>
</style>
