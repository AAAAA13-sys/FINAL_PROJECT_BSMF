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
                borderColor: '#e63946',
                backgroundColor: 'rgba(230, 57, 70, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#e63946',
                pointBorderColor: '#fff',
                pointHoverRadius: 6,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return ' Revenue: ₱' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { 
                        color: '#94a3b8', 
                        font: { size: 10 },
                        callback: function(value) { return '₱' + value.toLocaleString(); }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 10 } }
                }
            }
        }
    });
};
