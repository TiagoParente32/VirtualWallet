<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>
    <div>
      <h2>{{this.$store.state.user.name}}</h2>
      <img
        :src="`./storage/fotos/${this.$store.state.user.photo}`"
        class="img-thumbnail"
        height="200"
        width="200"
      />
      <br />

      <label for="email">Email</label>
      <input
        type="text"
        id="email"
        class="form-control"
        v-model="this.$store.state.user.email"
        readonly
      />
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
</template>

<script>
export default {
  data() {
    return {
      title: "Profile"
    };
  }
};
</script>

<style >
</style>
