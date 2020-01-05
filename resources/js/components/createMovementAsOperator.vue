<template>
  <div>
    <div class="jumbotron">
      <h1>Create Movement</h1>
    </div>
    <div>
      <div>
        <label for="value">Value</label>
        <input
          type="number"
          step="0.01"
          min="0"
          max="5000"
          id="value"
          class="form-control"
          required
          ref="value"
          v-model="movementData.value"
        />
      </div>
      <div>
        <label for="destinationEmail">Email of the destination wallet</label>
        <input
          type="text"
          id="destinationEmail"
          class="form-control"
          placeholder="Email"
          required
          v-model="movementData.email"
        />
      </div>
      <div>
        <label for="type">Type of Payment</label>
        <select class="form-control" id="type" name="type" v-model="movementData.type_payment">
          <option
            v-for="option in optionsPayment"
            :key="option.value"
            v-bind:value="option.value"
          >{{ option.text }}</option>
        </select>
      </div>

      <div v-if="movementData.type_payment == 'bt'">
        <div>
          <label for="iban">IBAN</label>
          <input
            type="text"
            class="form-control"
            name="iban"
            pattern="/([A-Z]){2}[0-9]{23}/"
            id="iban"
            v-model="movementData.iban"
          />
        </div>
        <div>
          <label for="sourceDescription">Source Description</label>
          <input
            type="text"
            class="form-control"
            name="sourceDescription"
            id="sourceDescription"
            v-model="movementData.source_description"
          />
        </div>
      </div>

      <br />
      <div v-if="error" class="alert alert-danger" role="alert">{{error}}</div>
      <hr />
      <div>
        <button type="button" class="btn btn-primary" v-on:click.prevent="createMovement">Create</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      movementData: {
        value: null,
        type_payment: null,
        iban: null,
        email: null,
        source_description: null
      },
      optionsPayment: [
        { text: "Bank Transfer", value: "bt" },
        { text: "Cash", value: "c" }
      ],
      error: null
    };
  },
  methods: {
    getCategories: function() {
      axios.get("api/categories/e").then(response => {
        this.categories = response.data.data;
      });
    },
    createMovement: function() {
      console.log(this.movementData);
      this.error = null;
      axios
        .post("api/movement/create", this.movementData)
        .then(response => {
          console.log(response);
          this.$socket.emit("teste", "aasdad");
          this.$socket.emit("userUpdated", this.movementData.email);
          this.$router.push("/profile");
        })
        .catch(err => {
          this.error = err.response.data.message;
          console.log(err.response.data);
        });
    }
  },
  mounted() {
    this.getCategories();
  },
  sockets: {
    connect() {
      console.log("socket connected (socketID = " + this.$socket.id + ")");
    }
  }
};
</script>

<style>
</style>
