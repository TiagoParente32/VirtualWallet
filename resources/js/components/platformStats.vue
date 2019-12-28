<template>
  <div class="small">
    <div class="jumbotron">
      <h1>User Statistics</h1>
    </div>

    <div class="row">
      <div class="col">
        <div class="card bg-secondary text-white">
          <h1 class="card-title" style="text-align:center;">Active Users</h1>
          <h4 class="card-body" style="text-align:center;">{{activeUsers}}</h4>
        </div>
      </div>
      <div class="col">
        <div class="card bg-secondary text-white">
          <h1 class="card-title" style="text-align:center;">New Users Last 30 Days</h1>
          <h4 class="card-body" style="text-align:center;">{{newUsers}}</h4>
        </div>
      </div>
    </div>
    <hr>
    <div class="jumbotron">
      <h1>Movement Statistics</h1>
    </div>

    <!-- <h4>Types of Payments</h4> -->
    <div class="row">
      <div class="col-8">
        <bar-chart
          v-if="this.PaymentLoaded"
          :chartData="chartPaymentData"
          :chartLabels="chartPaymentLabels"
          :title="titlePayments"
          :color="colorPayments"
        ></bar-chart>
      </div>
      <div class="col-4">
        <pie-chart
          v-if="this.PaymentLoaded"
          :chartData="chartPaymentData"
          :chartLabels="chartPaymentLabels"
        ></pie-chart>
      </div>
    </div>
  </div>
</template>

<script>
import WalletChart from "./lineChart";
import WalletBarChart from "./barChart";
import WalletPieChart from "./pieChart";
export default {
  components: {
    "line-chart": WalletChart,
    "bar-chart": WalletBarChart,
    "pie-chart": WalletPieChart
  },
  data() {
    return {
      chartPaymentData: [],
      chartPaymentLabels: [],
      PaymentLoaded: false,
      activeUsers: 0,
      newUsers: 0,
      colorPayments: "#FF8438",
      titlePayments: "Types of Payments"
    };
  },
  methods: {
    requestPaymentCount() {
      this.PaymentLoaded = false;
      axios
        .get("/api/statistics/countTypeOfPayment")
        .then(response => {
          this.chartPaymentLabels = response.data.map(d => {
            switch (d.type_payment) {
              case "c":
                return "Cash";
              case "mb":
                return "MB Payment";
              case "bt":
                return "Bank Transfer";
            }
          });

          this.chartPaymentData = response.data.map(d => {
            return d.count;
          });
          this.PaymentLoaded = true;
        })
        .catch(err => {
          console.log(err.response.data);
        });
    },
    requestActiveUsers() {
      axios
        .get("/api/statistics/countActiveUsers")
        .then(response => {
          this.activeUsers = response.data[0].count;
        })
        .catch(err => {
          console.log(err.response.data);
        });
    },
    requestNewUsers() {
      axios
        .get("/api/statistics/countRegistedUsers")
        .then(response => {
          this.newUsers = response.data[0].count;
        })
        .catch(err => {
          console.log(err.response.data);
        });
    }
  },
  mounted() {
    this.requestPaymentCount();
    this.requestActiveUsers();
    this.requestNewUsers();
  }
};
</script>

<style>
</style>
