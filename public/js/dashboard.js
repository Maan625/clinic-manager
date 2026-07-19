const chartData = document.getElementById('chart-data');
const canvas = document.getElementById('appointmentsChart');

if (chartData && canvas) {
    const labels = JSON.parse(chartData.dataset.labels);
    const data = JSON.parse(chartData.dataset.values);

    new Chart(canvas, {
        type: 'pie',

        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Appointments',
                    data: data,
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: true,
        }
    });
}