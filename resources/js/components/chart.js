import Chart from 'chart.js/auto';

class ChartRenderer {
    render(expenses, canvas) {
        if (!canvas) {
            console.error("Canvas not found!");
            return;
        }

        const ctx = canvas.getContext('2d');

        const categoryTotals = {};
        expenses.forEach(expense => {
            const categoryName = expense.category.name;
            if (!categoryTotals[categoryName]) {
                categoryTotals[categoryName] = 0;
            }
            categoryTotals[categoryName] += expense.amount;
        });

        const labels = Object.keys(categoryTotals);
        const data = Object.values(categoryTotals);

        this.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Expenses by Category',
                    data: data,
                    backgroundColor: [ /* Your colors */],
                    borderColor: [ /* Your border colors */],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

export default ChartRenderer;