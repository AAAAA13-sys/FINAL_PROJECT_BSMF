/**
 * BSMF Garage - Admin Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    // Admin Dashboard Initialized
});

/**
 * Initialize Revenue Chart
 * @param {Array} labels - Month names
 * @param {Array} data - Revenue values
 */
window.initRevenueChart = function(labels, data) {
    const chartEl = document.getElementById('revenueChart');
    if (!chartEl) return;

    const ctx = chartEl.getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue (₱)',
                data: data,
                borderColor: '#800C1F',
                backgroundColor: 'rgba(128, 12, 31, 0.1)',
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#7598B9',
                pointBorderColor: '#fff',
                pointHoverRadius: 8,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMin: 0,
                    suggestedMax: 1000,
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { 
                        color: '#94a3b8', 
                        font: { weight: 'bold' },
                        callback: function(value) { return '₱' + value.toLocaleString(); }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                }
            }
        }
    });
};
