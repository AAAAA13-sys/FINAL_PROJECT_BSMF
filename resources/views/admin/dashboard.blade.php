@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="admin-header-title">BSMF <span>DASHBOARD</span></h2>
        <div class="text-end">
            <div class="text-white-50 text-xs fst-italic fw-bold">{{ now()->format('l, jS F Y') }}</div>
            <div class="text-cool-slate text-xs fw-black text-uppercase">Admin: {{ Auth::user()->name }}</div>
        </div>
    </div>


    <!-- Stats Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card glass border-secondary p-4">
                <p class="text-cool-slate text-xs text-uppercase tracking-wider mb-2 fw-bolder">Total Revenue</p>
                <h3 class="text-cool-slate fw-black mb-0 fs-3 tracking-tighter">₱{{ number_format($totalSales, 2) }}</h3>
                <div class="progress mt-3 h-4px bg-glass">
                    <div class="progress-bar bg-warning w-100"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card glass border-primary p-4">
                <p class="text-primary text-xs text-uppercase tracking-wider mb-2 fw-bolder">Total Orders</p>
                <h3 class="text-white fw-black mb-0 fs-3 tracking-tighter">{{ $totalOrders }}</h3>
                <div class="progress mt-3 h-4px bg-glass">
                    <div class="progress-bar bg-primary w-100"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card glass border-info p-4">
                <p class="text-info text-xs text-uppercase tracking-wider mb-2 fw-bolder">Inventory (SKUs)</p>
                <h3 class="text-info fw-black mb-0 fs-3 tracking-tighter">{{ $totalProducts }}</h3>
                <div class="progress mt-3 h-4px bg-glass">
                    <div class="progress-bar bg-info w-100"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card glass border-success p-4">
                <p class="text-success text-xs text-uppercase tracking-wider mb-2 fw-bolder">Active Collectors</p>
                <h3 class="text-white fw-black mb-0 fs-3 tracking-tighter">{{ $totalCustomers }}</h3>
                <div class="progress mt-3 h-4px bg-glass">
                    <div class="progress-bar bg-success w-100"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart Section -->
    <div class="card glass border-secondary shadow-lg p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-white text-uppercase fst-italic mb-0 text-sm">REVENUE <span>PERFORMANCE</span></h6>
            <form action="{{ route('admin.dashboard') }}" method="GET" id="revenueFilterForm">
                <select name="revenue_filter" class="badge bg-dark border border-secondary p-1 px-2 text-uppercase fw-bold cursor-pointer text-xs" onchange="this.form.submit()">
                    <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>THIS WEEK</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>LAST 6 MONTHS</option>
                </select>
            </form>
        </div>
        <div class="h-180px">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="row g-3">
        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="card glass border-glass rounded-4 overflow-hidden shadow-lg bg-glass">
                <div class="card-header bg-transparent border-bottom-glass d-flex justify-content-between align-items-center p-4">
                    <h6 class="text-white text-uppercase fst-italic mb-0 text-sm tracking-wide">RECENT <span class="text-cool-slate">TRANSACTIONS</span></h6>
                    <a href="{{ route('admin.orders') }}" class="text-warm-bronze text-xs text-decoration-none fw-bold">VIEW ALL →</a>
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
                                            $statusClass = match($order->status) {
                                                'pending' => 'badge-pending',
                                                'processing' => 'badge-processing',
                                                'delivered' => 'badge-delivered',
                                                'cancelled' => 'badge-cancelled',
                                                default => 'bg-primary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} px-2 py-1 text-xs rounded-pill">
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
                    <div class="card-body p-3 max-h-140 overflow-auto">
                        @forelse($lowStockProducts as $lp)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                                <div>
                                    <h6 class="text-white mb-1 small fw-bold">{{ $lp->name }}</h6>
                                    <p class="text-danger small mb-0 fw-bold">{{ $lp->stock_quantity }} IN GARAGE</p>
                                </div>
                                <a href="{{ route('admin.products.edit', $lp->id) }}" class="btn btn-outline-danger btn-sm rounded-pill text-xs">REFILL</a>
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
@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="admin-header-title">BSMF <span>DASHBOARD</span></h2>
        <div class="text-end">
            <div class="text-white-50 text-xs fst-italic fw-bold">{{ now()->format('l, jS F Y') }}</div>
            <div class="text-cool-slate text-xs fw-black text-uppercase">Admin: {{ Auth::user()->name }}</div>
        </div>
    </div>


    <!-- Stats Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card glass border-secondary p-4">
                <p class="text-cool-slate text-xs text-uppercase tracking-wider mb-2 fw-bolder">Total Revenue</p>
                <h3 class="text-cool-slate fw-black mb-0 fs-3 tracking-tighter">₱{{ number_format($totalSales, 2) }}</h3>
                <div class="progress mt-3 h-4px bg-glass">
                    <div class="progress-bar bg-warning w-100"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card glass border-primary p-4">
                <p class="text-primary text-xs text-uppercase tracking-wider mb-2 fw-bolder">Total Orders</p>
                <h3 class="text-white fw-black mb-0 fs-3 tracking-tighter">{{ $totalOrders }}</h3>
                <div class="progress mt-3 h-4px bg-glass">
                    <div class="progress-bar bg-primary w-100"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card glass border-info p-4">
                <p class="text-info text-xs text-uppercase tracking-wider mb-2 fw-bolder">Inventory (SKUs)</p>
                <h3 class="text-info fw-black mb-0 fs-3 tracking-tighter">{{ $totalProducts }}</h3>
                <div class="progress mt-3 h-4px bg-glass">
                    <div class="progress-bar bg-info w-100"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card glass border-success p-4">
                <p class="text-success text-xs text-uppercase tracking-wider mb-2 fw-bolder">Active Collectors</p>
                <h3 class="text-white fw-black mb-0 fs-3 tracking-tighter">{{ $totalCustomers }}</h3>
                <div class="progress mt-3 h-4px bg-glass">
                    <div class="progress-bar bg-success w-100"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart Section -->
    <div class="card glass border-secondary shadow-lg p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-white text-uppercase fst-italic mb-0 text-sm">REVENUE <span>PERFORMANCE</span></h6>
            <form action="{{ route('admin.dashboard') }}" method="GET" id="revenueFilterForm">
                <select name="revenue_filter" class="badge bg-dark border border-secondary p-1 px-2 text-uppercase fw-bold cursor-pointer text-xs" onchange="this.form.submit()">
                    <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>THIS WEEK</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>LAST 6 MONTHS</option>
                </select>
            </form>
        </div>
        <div class="h-180px">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="row g-3">
        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="card glass border-glass rounded-4 overflow-hidden shadow-lg bg-glass">
                <div class="card-header bg-transparent border-bottom-glass d-flex justify-content-between align-items-center p-4">
                    <h6 class="text-white text-uppercase fst-italic mb-0 text-sm tracking-wide">RECENT <span class="text-cool-slate">TRANSACTIONS</span></h6>
                    <a href="{{ route('admin.orders') }}" class="text-warm-bronze text-xs text-decoration-none fw-bold">VIEW ALL →</a>
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
                                            $statusClass = match($order->status) {
                                                'pending' => 'badge-pending',
                                                'processing' => 'badge-processing',
                                                'delivered' => 'badge-delivered',
                                                'cancelled' => 'badge-cancelled',
                                                default => 'bg-primary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} px-2 py-1 text-xs rounded-pill">
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
                    <div class="card-body p-3 max-h-140 overflow-auto">
                        @forelse($lowStockProducts as $lp)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                                <div>
                                    <h6 class="text-white mb-1 small fw-bold">{{ $lp->name }}</h6>
                                    <p class="text-danger small mb-0 fw-bold">{{ $lp->stock_quantity }} IN GARAGE</p>
                                </div>
                                <a href="{{ route('admin.products.edit', $lp->id) }}" class="btn btn-outline-danger btn-sm rounded-pill text-xs">REFILL</a>
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
