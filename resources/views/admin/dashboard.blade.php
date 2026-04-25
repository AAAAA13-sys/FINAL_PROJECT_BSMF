@extends('layouts.admin')

@section('content')
<div class="fade-in p-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h2 text-white text-uppercase italic fw-black">BSMF <span>GARAGE CONTROL</span></h1>
        <div class="text-muted small italic fw-bold">{{ now()->format('l, jS F Y') }}</div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card p-4 glass border-secondary">
                <p class="text-muted small text-uppercase ls-2 mb-2">Total Revenue</p>
                <h2 class="text-warning fw-black mb-0">${{ number_format($totalSales, 2) }}</h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-4 glass border-primary">
                <p class="text-muted small text-uppercase ls-2 mb-2">Total Orders</p>
                <h2 class="text-white fw-black mb-0">{{ $totalOrders }}</h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-primary" style="width: 60%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-4 glass border-info">
                <p class="text-muted small text-uppercase ls-2 mb-2">Inventory (SKUs)</p>
                <h2 class="text-info fw-black mb-0">{{ $totalProducts }}</h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-info" style="width: 85%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-4 glass border-success">
                <p class="text-muted small text-uppercase ls-2 mb-2">Active Collectors</p>
                <h2 class="text-white fw-black mb-0">{{ $totalCustomers }}</h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-success" style="width: 90%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart Section -->
    <div class="card glass border-secondary p-4 mb-5 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-white text-uppercase italic mb-0">REVENUE <span>PERFORMANCE</span></h5>
            <div class="badge bg-dark border border-secondary p-2 px-3">LAST 6 MONTHS</div>
        </div>
        <div style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="card glass border-secondary rounded-4 overflow-hidden shadow-lg">
                <div class="card-header bg-transparent border-secondary p-4 d-flex justify-content-between align-items-center">
                    <h5 class="text-white text-uppercase italic mb-0">RECENT <span>TRANSACTIONS</span></h5>
                    <a href="{{ route('admin.orders') }}" class="text-warning small text-decoration-none fw-bold">VIEW ALL →</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4 border-secondary">Order #</th>
                                    <th class="border-secondary">Collector</th>
                                    <th class="border-secondary">Date</th>
                                    <th class="border-secondary">Amount</th>
                                    <th class="border-secondary pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td class="ps-4 text-warning fw-bold">{{ $order->order_number }}</td>
                                    <td class="text-white fw-bold">{{ $order->customer_name }}</td>
                                    <td class="text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="text-white fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="pe-4">
                                        @php
                                            $badgeClass = match($order->status) {
                                                'pending' => 'bg-info',
                                                'processing' => 'bg-warning',
                                                'delivered' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                default => 'bg-primary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} text-dark px-3 py-2" style="font-size: 0.65rem; border-radius: 30px;">
                                            {{ strtoupper($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side Alerts -->
        <div class="col-md-4">
            <div class="d-flex flex-column gap-4">
                <!-- Stock Alerts -->
                <div class="card glass border-danger rounded-4 shadow-lg">
                    <div class="card-header bg-transparent border-danger p-4">
                        <h5 class="text-danger text-uppercase italic mb-0">LOW <span>STOCK</span></h5>
                    </div>
                    <div class="card-body p-4">
                        @forelse($lowStockProducts as $lp)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                                <div>
                                    <h6 class="text-white mb-1 small fw-bold">{{ $lp->name }}</h6>
                                    <p class="text-danger small mb-0 fw-bold">{{ $lp->stock_quantity }} IN GARAGE</p>
                                </div>
                                <a href="{{ route('admin.products.edit', $lp->id) }}" class="btn btn-outline-danger btn-sm rounded-pill" style="font-size: 0.6rem;">REFILL</a>
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <i class="fas fa-check-circle text-success mb-2"></i>
                                <p class="text-muted small italic mb-0">Stock Levels Optimal</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Disputes -->
                <div class="card glass border-warning rounded-4 shadow-lg">
                    <div class="card-header bg-transparent border-warning p-4">
                        <h5 class="text-warning text-uppercase italic mb-0">ACTIVE <span>DISPUTES</span></h5>
                    </div>
                    <div class="card-body p-4">
                        @forelse($recentDisputes as $dispute)
                            <div class="mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-white small fw-bold">#{{ $dispute->order->order_number }}</span>
                                    <span class="text-warning small italic fw-bold">{{ strtoupper($dispute->dispute_type) }}</span>
                                </div>
                                <p class="text-muted small mb-2 text-truncate">{{ $dispute->description }}</p>
                                <a href="{{ route('admin.disputes') }}" class="text-warning small text-decoration-none fw-bold hover-underline">INTERVENE →</a>
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <i class="fas fa-handshake text-warning mb-2"></i>
                                <p class="text-muted small italic mb-0">No Active Disputes</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const labels = {!! json_encode($monthlySales->pluck('month')) !!};
        const data = {!! json_encode($monthlySales->pluck('total')) !!};

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
    });
</script>

<style>
    .fw-black { font-weight: 900; }
    .ls-2 { letter-spacing: 2px; }
    .italic { font-style: italic; }
    .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: default; }
    .stat-card:hover { transform: translateY(-10px); background: rgba(255,255,255,0.05) !important; }
    .hover-underline:hover { text-decoration: underline !important; }
</style>
@endsection

