import $ from 'jquery';
import Chart from 'chart.js/auto';
import Loading from './loading';

class ChartComponent {
    constructor(chartContainerSelector) {
        this.chartContainer = $(chartContainerSelector);
        this.chart = null;
        this.loading = new Loading('#loading-indicator');
        this.fetchExpenseSummary();
    }

    fetchExpenseSummary(startDate = '2024-01-01', endDate = '2024-01-31') {
        let start_date = new Date();
        start_date.setMonth(start_date.getMonth() - 2);
        // let end_date = new Date();
        // end date should be now present time 
        let end_date = new Date();
        this.loading.show();
        let url = '/api/expense-summary';

        axios.get(url, {
            params: {
                start_date: start_date.toISOString().slice(0, 10),
                end_date: end_date.toISOString().slice(0, 10)
            }
        })
            .then(response => {
                this.renderChart(response.data);
            })
            .catch(error => {
                console.error("Error fetching expense summary:", error);
                this.chartContainer.html('<p>Error loading chart data.</p>');
            })
            .finally(() => {
                this.loading.hide();
            });
    }

    renderChart(summaryData) {
        if (this.chart) {
            this.chart.destroy();
            this.chart = null;
        }

        if (summaryData.length === 0) {
            this.chartContainer.html('<p>No expense data for the selected period.</p>');
            return;
        }

        const labels = summaryData.map(item => item.category);
        const data = summaryData.map(item => item.total_amount);

        // Get or create the canvas element (as before)

        let canvas = this.chartContainer.find('#expense-summary-chart');
        if (canvas.length) {
            canvas.remove(); // Remove the old canvas
        }
        canvas = $('<canvas id="expense-summary-chart"></canvas>'); // Create a new canvas
        this.chartContainer.append(canvas); // Add the new canvas to the container
        const ctx = canvas.get(0).getContext('2d');

        // *** THE DELAY (WORKAROUND - LESS RELIABLE) ***
        setTimeout(() => {  // Create the chart after a small delay
            this.chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Expenses by Category',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)', // ... your colors
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)', // ... your border colors
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Expense Summary by Category'
                        }
                    }
                }
            });
        }, 100);
    }
}

export default ChartComponent;