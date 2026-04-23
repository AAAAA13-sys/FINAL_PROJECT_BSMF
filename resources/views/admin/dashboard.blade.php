@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <h1 style="margin-bottom: 2.5rem; font-style: italic; text-transform: uppercase; font-weight: 900; letter-spacing: -1px;">Dashboard <span>Overview</span></h1>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 2.5rem;">
        <div class="stat-card">
            <p style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem;">Total Sales</p>
            <h2 style="color: var(--secondary); font-size: 1.8rem; font-weight: 900;">${{ number_format($totalSales, 2) }}</h2>
        </div>
        <div class="stat-card" style="border-color: var(--secondary);">
            <p style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem;">Total Orders</p>
            <h2 style="color: var(--primary); font-size: 1.8rem; font-weight: 900;">{{ $totalOrders }}</h2>
        </div>
        <div class="stat-card" style="border-color: #60a5fa;">
            <p style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem;">Inventory</p>
            <h2 style="color: #60a5fa; font-size: 1.8rem; font-weight: 900;">{{ $totalProducts }} <small style="font-size: 0.7rem; opacity: 0.6;">SKUs</small></h2>
        </div>
    </div>

    <div class="glass" style="padding: 2rem; border-radius: 16px; margin-bottom: 4rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; font-style: italic; font-size: 1rem; text-transform: uppercase;">Revenue <span>Performance</span></h3>
            <select id="viewSelector" class="form-control" style="width: 150px; padding: 0.5rem; background: rgba(0,0,0,0.3); color: white; border-color: var(--glass-border); font-size: 0.8rem; font-weight: 700;">
                <option value="monthly">Monthly View</option>
                <option value="yearly">Yearly View</option>
            </select>
        </div>
        <div style="height: 250px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const now = new Date();
        const currentMonth = now.getMonth(); // 0-11
        const currentDay = now.getDate();

        const allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const allDays = ['1st', '5th', '10th', '15th', '20th', '25th', '30th'];
        
        // Initial setup for Monthly (Days)
        let initialLabels = allDays.filter(day => parseInt(day) <= currentDay || day === '1st');
        if (initialLabels.length === 0) initialLabels = ['1st'];
        
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialLabels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: initialLabels.map((_, i) => i === initialLabels.length - 1 ? {{ $totalSales }} : 0),
                    borderColor: '#e11d48',
                    backgroundColor: 'rgba(225, 29, 72, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fbbf24'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                }
            }
        });

        // Toggle Monthly/Yearly Logic
        document.getElementById('viewSelector').addEventListener('change', function() {
            if(this.value === 'yearly') {
                // Annual View: Show only up to current month
                const yearlyLabels = allMonths.slice(0, currentMonth + 1);
                salesChart.data.labels = yearlyLabels;
                salesChart.data.datasets[0].data = yearlyLabels.map((_, i) => i === yearlyLabels.length - 1 ? {{ $totalSales }} : 0);
            } else {
                // Monthly View: Show only up to current day
                const monthlyLabels = allDays.filter(day => parseInt(day) <= currentDay || day === '1st');
                salesChart.data.labels = monthlyLabels;
                salesChart.data.datasets[0].data = monthlyLabels.map((_, i) => i === monthlyLabels.length - 1 ? {{ $totalSales }} : 0);
            }
            salesChart.update();
        });
    </script>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <div>
            <h2 style="margin-bottom: 2rem; text-transform: uppercase; font-style: italic;">Recent <span>Orders</span></h2>
            <div class="glass" style="padding: 1rem; border-radius: 16px; overflow: hidden; margin-bottom: 3rem;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td style="color: var(--text-muted);">#BFMSL-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td style="font-weight: 800; text-transform: uppercase;">{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td style="color: var(--secondary); font-weight: 900;">${{ number_format($order->total_price, 2) }}</td>
                            <td>
                                @php
                                    $color = match($order->status) {
                                        'Order Placed' => '#60a5fa',
                                        'Processing' => '#fbbf24',
                                        'Out for Delivery' => '#ec4899',
                                        'Delivered' => '#10b981',
                                        default => 'var(--primary)'
                                    };
                                @endphp
                                <span class="status-badge" style="background: {{ $color }}1a; border: 1px solid {{ $color }}; color: {{ $color }}; font-size: 0.7rem;">{{ $order->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h2 style="margin-bottom: 2rem; text-transform: uppercase; font-style: italic;">Stock <span>Alerts</span></h2>
            <div class="glass" style="padding: 2rem; border-radius: 16px;">
                @forelse($lowStockProducts as $lp)
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                        <div>
                            <p style="font-weight: 800; font-size: 0.9rem; text-transform: uppercase;">{{ $lp->name }}</p>
                            <p style="font-size: 0.7rem; color: var(--text-muted);">ONLY <span style="color: var(--danger); font-weight: 900;">{{ $lp->stock }}</span> LEFT</p>
                        </div>
                        <a href="{{ route('admin.products.edit', $lp->id) }}" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.7rem; background: var(--danger)1a; color: var(--danger); border: 1px solid var(--danger);">Refill</a>
                    </div>
                @empty
                    <p style="color: var(--text-muted); text-align: center; font-style: italic;">All stock levels are optimal.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
