import Chart from 'chart.js/auto';
import 'chartjs-adapter-moment';

Chart.defaults.borderColor = 'rgb(75 85 99)';
Chart.defaults.color = 'rgb(75 85 99)';

function loadDpsChart() {
    new Chart(
        document.getElementById('dpsHistory'),
        {
            type: 'line',
            options: {
                responsive: true,
                aspectRatio: false,
                scales: {
                    x: {
                        type: 'timeseries',
                        time: {
                            unit: 'day',
                        }
                    }
                }
            },
            data: {
                datasets: dpsHistoryDatasets,
            }
        }
    )
}

loadDpsChart();
