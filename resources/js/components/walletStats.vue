<template>
  <div class="small">
    <div class="jumbotron">
      <h1>Balance last 30 days</h1>
    </div>

    <line-chart v-if="this.loaded" :chartData="chartdata" :chartLabels="chartlabels"></line-chart>
    <br />
    <div class="jumbotron">
      <h1>Expenses/Income per Category last 30 days</h1>
    </div>
    <bar-chart
      v-if="this.loaded"
      :chartData="chartBarData"
      :chartLabels="chartBarLabels"
      :title="expense"
      :color="colorExp"
    ></bar-chart>
    <br />
    <bar-chart
      v-if="this.loaded"
      :chartData="chartBarIncomeData"
      :chartLabels="chartBarIncomeLabels"
      :title="income"
      :color="colorInc"
    ></bar-chart>
    <br />
    <pie-chart
      v-if="this.loaded"
      :chartData="chartBarIncomeData"
      :chartLabels="chartBarIncomeLabels"
      :colors="colorArrayInc"
    ></pie-chart>
    <br />
    <pie-chart
      v-if="this.loaded"
      :chartData="chartBarData"
      :chartLabels="chartBarLabels"
      :colors="colorArrayExp"
    ></pie-chart>
  </div>
</template>

<script>
import WalletChart from "./walletChart";
import WalletBarChart from "./walletBarChart";
import WalletPieChart from "./walletPieChart";
export default {
  components: {
    "line-chart": WalletChart,
    "bar-chart": WalletBarChart,
    "pie-chart": WalletPieChart
  },
  data() {
    return {
      chartdata: [],
      chartlabels: [],
      chartBarData: [],
      chartBarLabels: [],
      chartBarIncomeData: [],
      chartBarIncomeLabels: [],
      loaded: false,
      filterData: {
        dataMin: null,
        dataMax: null
      },
      expense: "Expense per Category",
      income: "Income per Category",
      colorInc: "#008000",
      colorExp: "#f87979",
      colorArrayInc: [],
      colorArrayExp: []
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
    requestExpensesCategories() {
      axios
        .get("/api/users/me/wallet/movements/sumExpensesPerCategory/e")
        .then(response => {
          //console.log("categorias");
          //console.log(response.data);
          this.chartBarLabels = response.data.map(d => {
            return d.name;
          });

          this.chartBarData = response.data.map(d => {
            return d.sum;
          });
        })
        .catch(err => {
          console.log(err.response.data);
        });
    },
    requestIncomeCategories() {
      axios
        .get("/api/users/me/wallet/movements/sumExpensesPerCategory/i")
        .then(response => {
          this.chartBarIncomeLabels = response.data.map(d => {
            return d.name;
          });

          this.chartBarIncomeData = response.data.map(d => {
            return d.sum;
          });
        })
        .catch(err => {
          console.log(err.response.data);
        });
    }
  },
  mounted() {
    this.requestIncomeCategories();
    this.requestExpensesCategories();
    this.requestData();
  }
};
</script>

<style>
</style>
