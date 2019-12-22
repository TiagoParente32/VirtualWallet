<template>
  <div>
    <div class="jumbotron">
      <h1>Create Movement</h1>
    </div>
    <div>
      <div>
        <label for="type">Type of Transfer</label>
        <select class="form-control" id="type" name="type" v-model="movementData.typeOfTransfer">
          <option
            v-for="option in optionsTransfer"
            :key="option.value"
            v-bind:value="option.value"
          >{{ option.text }}</option>
        </select>
      </div>
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
        <label for="type">Category</label>

        <select
          class="form-control"
          id="category_id"
          name="category"
          v-model="movementData.category"
        >
          <option
            v-for="category in categories"
            :key="category.id"
            v-bind:value="category.id"
          >{{ category.name }}</option>
        </select>
      </div>

      <div>
        <label for="description">Description</label>
        <input
          type="text"
          class="form-control"
          name="description"
          id="description"
          v-model="movementData.description"
        />
      </div>
      <div v-if="movementData.typeOfTransfer == 0">
        <div>
          <label for="type">Type of Payment</label>
          <select class="form-control" id="type" name="type" v-model="movementData.typeOfPayment">
            <option
              v-for="option in optionsPayment"
              :key="option.value"
              v-bind:value="option.value"
            >{{ option.text }}</option>
          </select>
        </div>

        <div v-if="movementData.typeOfPayment == 'bt'">
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

        <div v-if="movementData.typeOfPayment == 'mb'">
          <label for="entity">Entity</label>
          <input
            type="text"
            class="form-control"
            name="entity"
            pattern="\b\d{5}\b"
            id="entity"
            v-model="movementData.entity"
          />

          <label for="reference">Reference</label>
          <input
            type="text"
            class="form-control"
            name="reference"
            pattern="\b\d{9}\b"
            id="reference"
            v-model="movementData.reference"
          />
        </div>
      </div>

      <div v-if="movementData.typeOfTransfer == 1">
        <div>
          <label for="destinationEmail">Email of the destination wallet</label>
          <input
            type="text"
            id="destinationEmail"
            class="form-control"
            placeholder="Email"
            required
            v-model="movementData.destinationEmail"
          />
        </div>

        <div>
          <label for="sourceDescription">Source Description</label>
          <input
            type="text"
            class="form-control"
            name="sourceDescription"
            id="sourceDescription"
            v-model="movementData.sourceDescription"
          />
        </div>
      </div>
      <br />
      <div>
        <button type="button" class="btn btn-primary">Login</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      movementData: {
        typeOfTransfer: null,
        value: null,
        category: null,
        description: null,
        typeOfPayment: null,
        iban: null,
        entity: null,
        reference: null,
        destinationEmail: null,
        sourceDescription: null
      },
      categories: [],
      optionsTransfer: [
        { text: "Payment To External Entity", value: 0 },
        { text: "Transfer", value: 1 }
      ],
      optionsPayment: [
        { text: "Bank Transfer", value: "bt" },
        { text: "MB Payment", value: "mb" }
      ]
    };
  },
  methods: {
    getCategories: function() {
      axios.get("api/categories/e").then(response => {
        this.categories = response.data.data;
      });
    }
  },
  mounted() {
    this.getCategories();
  }
};
</script>

<style>
</style>
