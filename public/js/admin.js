/**
 * BSMF Garage - Admin Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Admin Dashboard Initialized');
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
                label: 'Revenue ($)',
                data: data,
                borderColor: '#fbbf24',
                backgroundColor: 'rgba(251, 191, 36, 0.1)',
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#e11d48',
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
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                }
            }
        }
    });
};
