@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 2rem; color: white; text-transform: uppercase; font-style: italic; font-weight: 900;">BSMF <span style="color: var(--secondary);">DASHBOARD</span></h2>
        <div style="text-align: right;">
            <div style="color: rgba(255,255,255,0.5); font-size: 0.75rem; font-style: italic; font-weight: bold;">{{ now()->format('l, jS F Y') }}</div>
            <div style="color: var(--secondary); font-size: 0.75rem; font-weight: 900; text-transform: uppercase;">Admin: {{ Auth::user()->name }}</div>
        </div>
    </div>


    <!-- Stats Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card glass border-secondary" style="padding: 1.25rem 1.5rem;">
                <p style="color: var(--secondary); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem; font-weight: 800;">Total Revenue</p>
                <h3 style="color: var(--secondary); font-weight: 900; margin-bottom: 0; font-size: 1.75rem; letter-spacing: -0.5px;">₱{{ number_format($totalSales, 2) }}</h3>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card glass" style="border: 1px solid #0d6efd; padding: 1.25rem 1.5rem;">
                <p style="color: #0d6efd; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem; font-weight: 800;">Total Orders</p>
                <h3 style="color: white; font-weight: 900; margin-bottom: 0; font-size: 1.75rem; letter-spacing: -0.5px;">{{ $totalOrders }}</h3>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card glass border-info" style="padding: 1.25rem 1.5rem;">
                <p style="color: #0dcaf0; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem; font-weight: 800;">Inventory (SKUs)</p>
                <h3 style="color: #0dcaf0; font-weight: 900; margin-bottom: 0; font-size: 1.75rem; letter-spacing: -0.5px;">{{ $totalProducts }}</h3>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card glass border-success" style="padding: 1.25rem 1.5rem;">
                <p style="color: #198754; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem; font-weight: 800;">Active Collectors</p>
                <h3 style="color: white; font-weight: 900; margin-bottom: 0; font-size: 1.75rem; letter-spacing: -0.5px;">{{ $totalCustomers }}</h3>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart Section -->
    <div class="card glass border-secondary shadow-lg" style="padding: 1rem; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h6 style="color: white; text-transform: uppercase; font-style: italic; margin-bottom: 0; font-size: 0.85rem;">REVENUE <span>PERFORMANCE</span></h6>
            <form action="{{ route('admin.dashboard') }}" method="GET" id="revenueFilterForm">
                <select name="revenue_filter" class="badge bg-dark border border-secondary p-1 px-2 text-uppercase fw-bold cursor-pointer" onchange="this.form.submit()" style="font-size: 0.65rem;">
                    <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>THIS WEEK</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>LAST 6 MONTHS</option>
                </select>
            </form>
        </div>
        <div style="height: 180px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="row g-3">
        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="card glass border-secondary rounded-4 overflow-hidden shadow-lg">
                <div class="card-header bg-transparent border-secondary d-flex justify-content-between align-items-center" style="padding: 0.75rem 1rem;">
                    <h6 style="color: white; text-transform: uppercase; font-style: italic; margin-bottom: 0; font-size: 0.85rem;">RECENT <span>TRANSACTIONS</span></h6>
                    <a href="{{ route('admin.orders') }}" style="color: #fbbf24; font-size: 0.75rem; text-decoration: none; font-weight: bold;">VIEW ALL →</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-3 border-secondary">Order #</th>
                                    <th class="border-secondary">Collector</th>
                                    <th class="border-secondary">Date</th>
                                    <th class="border-secondary">Amount</th>
                                    <th class="border-secondary pe-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td class="ps-4 text-warning fw-bold"><a href="{{ route('admin.orders.show', $order->id) }}" class="text-warning text-decoration-none hover-underline">{{ $order->order_number }}</a></td>
                                    <td class="text-white fw-bold">{{ $order->customer_name }}</td>
                                    <td class="text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="text-white fw-bold">₱{{ number_format($order->total_amount, 2) }}</td>
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
                                        <span class="badge {{ $badgeClass }} text-dark px-2 py-1" style="font-size: 0.6rem; border-radius: 30px;">
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
                    <div class="card-header bg-transparent border-danger p-3">
                        <h6 class="text-danger text-uppercase italic mb-0">LOW <span>STOCK</span></h6>
                    </div>
                    <div class="card-body p-3">
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


            </div>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initRevenueChart(
                {!! json_encode($salesData->pluck('label')) !!},
                {!! json_encode($salesData->pluck('total')) !!}
            );
        });
    </script>


@endsection

