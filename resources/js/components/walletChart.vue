
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
    this.renderChart(
      {
        labels: this.chartLabels,
        datasets: [
          {
            label: "Balance",
            backgroundColor: "#4267B2",
            data: this.chartData
          }
        ]
      },
      {
        responsive: true,
        maintainAspectRatio: false,
        //remove gridlines
        scales: {
          xAxes: [
            {
              gridLines: {
                color: "rgba(0, 0, 0, 0)"
              }
            }
          ],
          yAxes: [
            {
              gridLines: {
                color: "rgba(0, 0, 0, 0)"
              }
            }
          ]
        }
      }
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
