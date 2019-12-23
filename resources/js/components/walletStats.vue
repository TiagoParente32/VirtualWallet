<template>
  <div class="small">
    <div class="jumbotron">
      <h1>Balance last 30 days</h1>
    </div>
    <wallet-balance-chart v-if="this.loaded" :chartData="chartdata" :chartLabels="chartlabels"></wallet-balance-chart>
    <wallet-category-chart
      v-if="this.loaded"
      :chartData="chartBarData"
      :chartLabels="chartBarLabels"
    ></wallet-category-chart>
  </div>
</template>

<script>
import WalletChart from "./walletChart";
import WalletBarChart from "./walletBarChart";
export default {
  components: {
    "wallet-balance-chart": WalletChart,
    "wallet-category-chart": WalletBarChart
  },
  data() {
    return {
      chartdata: [],
      chartlabels: [],
      chartBarData: [],
      chartBarLabels: [],
      loaded: false,
      filterData: {
        dataMin: null,
        dataMax: null
      }
    };
  },
  methods: {
    requestData() {
      this.loaded = false;
      this.filterData.dataMax = new Date();
      this.filterData.dataMin = new Date();
      this.filterData.dataMin.setDate(this.filterData.dataMax.getDate() - 30);
      axios
        .post(`/api/users/me/wallet/movements/filter`, this.filterData)
        .then(response => {
          this.chartdata = response.data.data.map(d => {
            return d.end_balance;
          });
          this.chartlabels = response.data.data.map(d => {
            return d.date;
          });
          this.loaded = true;
        })
        .catch(err => {
          console.log(err.response.data);
        });
    },
    requestCategories() {
      axios
        .get("/api/categories")
        .then(response => {
          this.chartBarLabels = response.data.data;
        })
        .catch(err => {
          console.log(err.response.data);
        });
    }
  },
  mounted() {
    this.requestCategories();
    this.requestData();
  }
};
</script>

<style>
</style>
