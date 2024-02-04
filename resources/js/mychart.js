
import Chart from 'chart.js/auto';


const data = {
    labels: etiqueta,
    datasets: [{
        label: 'Ganancias',
        backgroundColor: [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
        ],
        borderColor: 'rgb(255, 99, 132)',
        data: ganancias,
    }]
};

const config = {
    type: 'doughnut',
    data: data,
    options: {}
};

new Chart(
    document.getElementById('myChart'),
    config
);
