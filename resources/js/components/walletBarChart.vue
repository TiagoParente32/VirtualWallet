
<script>
import { Bar } from "vue-chartjs";
export default {
  extends: Bar,
  props: {
    chartData: {
      type: Array,
      required: true
    },
    chartLabels: {
      type: Array,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    color: {
      type: String,
      required: true
    }
  },

  mounted() {
    // console.log(this.chartLabels);
    // console.log(this.chartData);
    //this.renderChart(this.chartLabels, this.chartData, this.options);
    this.renderChart(
      {
        labels: this.chartLabels,
        datasets: [
          {
            label: this.title,
            backgroundColor: this.color,
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
      this.renderChart(
        this.chartLabels.map(d => d.name),
        this.chartData,
        this.options
      );
    },
    chartLabels() {
      this.renderChart(
        this.chartLabels.map(d => d.name),
        this.chartData,
        this.options
      );
    }
  }
};
</script>

<style >
</style>
