<template>
  <div class="jumbotron">
    <h2>Movement Details</h2>
    <br />
    <table class="table table-bordered">
      <thead align="center">
        <tr style="color : white">
          <th>Photo</th>
          <th>Description</th>
          <th>Source Description</th>
          <th>IBAN</th>
          <th>MB Entity Code</th>
          <th>MB Payment Reference</th>
        </tr>
      </thead>
      <tbody>
        <tr style="color : white;" align="center">
          <template v-if="currentMovement.transfer_wallet != null">
            <td v-if="currentMovement.transfer_wallet.photo !== null">
              <img
                :src="`./storage/fotos/${currentMovement.transfer_wallet.photo}`"
                class="img-thumbnail"
                height="100px"
                width="100px"
                max-width="100px"
                max-height="100px"
              />
            </td>
            <td v-else>
              <img
                :src="'./storage/fotos/default.png'"
                class="img-thumbnail"
                height="100px"
                width="100px"
                max-width="100px"
                max-height="100px"
              />
            </td>
          </template>
          <td v-else>-</td>
          <td v-if="currentMovement.description">{{currentMovement.description}}</td>
          <td v-else>-</td>
          <td v-if="currentMovement.source_description">{{currentMovement.source_description}}</td>
          <td v-else>-</td>
          <td v-if="currentMovement.iban">{{currentMovement.iban}}</td>
          <td v-else>-</td>
          <td v-if="currentMovement.mb_entity_code">{{currentMovement.mb_entity_code}}</td>
          <td v-else>-</td>
          <td v-if="currentMovement.mb_payment_reference">{{currentMovement.mb_payment_reference}}</td>
          <td v-else>-</td>
        </tr>
      </tbody>
    </table>
    <a
      style="color: white; float: right;"
      class="btn btn-primary btn-sm"
      v-on:click.prevent="close()"
    >Close</a>
  </div>
</template>

<script>
export default {
  props: ["currentMovement"],
  methods: {
    close() {
      this.$emit("close");
    }
  }
};
</script>

<style>
</style>
