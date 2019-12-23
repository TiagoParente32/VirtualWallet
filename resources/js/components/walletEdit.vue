<template>
  <div class="jumbotron" id="edit">
    <h2>Edit Movement</h2>
    <div class="form-group">
      <label for="categories_id">Category:</label>
      <select
        class="form-control"
        id="category_id"
        name="category"
        v-model="currentMovement.category.id"
      >
        <!-- <option value="-1">NA</option>  FALTA VER SE QUISER TIRAR CATEGORIA-->
        <option
          v-for="category in categories"
          :key="category.id"
          v-bind:value="category.id"
        >{{ category.name }}</option>
      </select>
    </div>

    <div class="form-group">
      <label for="inputDescription">Description</label>
      <input
        type="text"
        class="form-control"
        v-model="currentMovement.description"
        name="description"
        id="inputDescription"
      />
    </div>

    <div class="form-group">
      <a class="btn btn-primary" v-on:click.prevent="saveMovement()">Save</a>
      <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
    </div>
  </div>
</template>

<script>
export default {
  props: ["currentMovement"],
  data: function() {
    return { categories: [] };
  },
  methods: {
    getCategories: function() {
      //console.log(this.currentMovement.type);
      axios
        .get("api/categories/" + this.currentMovement.type)
        .then(response => {
          this.categories = response.data.data;
        });
    },
    saveMovement: function() {
      //console.log(this.currentMovement);
      this.$emit("save-movement", this.currentMovement);
    },
    cancelEdit: function() {
      this.$emit("cancel-edit");
    }
  },
  mounted() {
    this.getCategories();
  },
  watch: {
    currentMovement: function() {
      this.getCategories();
    }
  }
};
</script>

<style>
</style>
