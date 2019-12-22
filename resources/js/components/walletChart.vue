
<script>
import { Line } from "vue-chartjs";
export default {
  extends: Line,
  props: {
    chartData: {
      type: Array,
      required: true
    },
    chartLabels: {
      type: Array,
      required: true
    }
  },

  mounted() {
    console.log(this.chartLabels);
    console.log(this.chartData);
    //this.renderChart(this.chartLabels, this.chartData, this.options);
    this.renderChart(
      {
        labels: this.chartLabels,
        datasets: [
          {
            label: "Balance",
            backgroundColor: "#f87979",
            data: this.chartData
          }
        ]
      },
      { responsive: true, maintainAspectRatio: false }
    );
  },
  watch: {
    chartData() {
      this.renderChart(this.chartLabels, this.chartData, this.options);
    },
    chartLabels() {
      this.renderChart(this.chartLabels, this.chartData, this.options);
    }
  }
};
</script>

<style >
</style>
