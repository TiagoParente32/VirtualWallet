<script>
import { Pie } from "vue-chartjs";
export default {
  extends: Pie,
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
            label: "Income",
            backgroundColor: [
              "#63b598",
              "#ce7d78",
              "#ea9e70",
              "#a48a9e",
              "#c6e1e8",
              "#648177",
              "#0d5ac1",
              "#f205e6",
              "#1c0365",
              "#14a9ad",
              "#4ca2f9",
              "#a4e43f",
              "#d298e2",
              "#6119d0",
              "#d2737d",
              "#c0a43c",
              "#f2510e",
              "#651be6",
              "#79806e",
              "#61da5e",
              "#cd2f00",
              "#21d52e",
              "#d00043",
              "#b47162",
              "#1ec227",
              "#4f0f6f",
              "#1d1d58",
              "#947002",
              "#bde052",
              "#e08c56",
              "#28fcfd",
              "#bb09b",
              "#36486a",
              "#d02e29",
              "#1ae6db",
              "#3e464c",
              "#a84a8f",
              "#911e7e",
              "#3f16d9",
              "#0f525f",
              "#ac7c0a",
              "#b4c086",
              "#c9d730",
              "#30cc49",
              "#3d6751",
              "#fb4c03",
              "#640fc1",
              "#62c03e",
              "#d3493a",
              "#88aa0b",
              "#406df9"
            ],

            data: this.chartData
          }
        ]
      },
      {
        borderWidth: "10px",
        hoverBorderWidth: "10px"
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

<style>
</style>
