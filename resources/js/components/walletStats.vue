<template>
  <div class="small">
    <wallet-chart v-if="this.loaded" :chartData="chartdata" :chartLabels="chartlabels"></wallet-chart>
  </div>
</template>

<script>
import WalletChart from "./walletChart";
export default {
  components: {
    "wallet-chart": WalletChart
  },
  data() {
    return {
      chartdata: [],
      chartlabels: [],
      loaded: false
    };
  },
  methods: {
    requestData() {
      this.loaded = false;
      axios.get(`api/users/me/wallet/movements?page=1`).then(response => {
        this.chartdata = response.data.data.map(d => {
          return d.end_balance;
        });
        this.chartlabels = response.data.data.map(d => {
          return d.date;
        });
        this.loaded = true;
      });
    }
  },
  mounted() {
    this.requestData();
  }
};
</script>

<style>
</style>
