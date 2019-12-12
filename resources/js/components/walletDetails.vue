<template>
  <table
    class="table table-bordered"
    :key="movement.id + '-details'"
    v-if="opened.includes(movement.id)"
  >
    <thead align="center">
      <tr>
        <th>Photo</th>
        <th>Description</th>
        <th>Source Description</th>
        <th>IBAN</th>
        <th>MB Entity Code</th>
        <th>MB Payment Reference</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <template v-if="movement.transfer_wallet != null">
          <td v-if="movement.transfer_wallet.photo !== null">
            <img
              :src="`./storage/fotos/${movement.transfer_wallet.photo}`"
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
        <td>{{movement.description}}</td>
        <td>{{movement.source_description}}</td>
        <td>{{movement.iban}}</td>
        <td>{{movement.mb_entity_code}}</td>
        <td>{{movement.mb_payment_reference}}</td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {};
</script>

<style>
</style>
