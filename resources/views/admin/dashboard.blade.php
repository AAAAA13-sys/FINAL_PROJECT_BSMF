@extends('layouts.admin')

@section('content')
<div class="fade-in p-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h3 text-white text-uppercase italic fw-black">BSMF <span style="color: var(--secondary);">GARAGE CONTROL</span></h1>
        <div class="text-end">
            <div class="text-white-50 small italic fw-bold">{{ now()->format('l, jS F Y') }}</div>
            <div class="text-secondary small fw-black text-uppercase">Admin: {{ Auth::user()->name }}</div>
        </div>
    </div>


    <!-- Stats Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card p-3 glass border-secondary">
                <p class="text-secondary small text-uppercase ls-2 mb-1" style="font-size: 0.65rem; font-weight: 800;">Total Revenue</p>
                <h2 class="text-warning fw-black mb-0">${{ number_format($totalSales, 2) }}</h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-3 glass border-primary">
                <p class="text-white opacity-75 small text-uppercase ls-2 mb-1" style="font-size: 0.65rem; font-weight: 800;">Total Orders</p>
                <h2 class="text-white fw-black mb-0">{{ $totalOrders }}</h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-3 glass border-info">
                <p class="text-info small text-uppercase ls-2 mb-1" style="font-size: 0.65rem; font-weight: 800;">Inventory (SKUs)</p>
                <h2 class="text-info fw-black mb-0">{{ $totalProducts }}</h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-3 glass border-success">
                <p class="text-success small text-uppercase ls-2 mb-1" style="font-size: 0.65rem; font-weight: 800;">Active Collectors</p>
                <h2 class="text-white fw-black mb-0">{{ $totalCustomers }}</h2>
                <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart Section -->
    <div class="card glass border-secondary p-3 mb-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-white text-uppercase italic mb-0">REVENUE <span>PERFORMANCE</span></h5>
            <form action="{{ route('admin.dashboard') }}" method="GET" id="revenueFilterForm">
                <select name="revenue_filter" class="badge bg-dark border border-secondary p-2 px-3 text-uppercase fw-bold cursor-pointer" onchange="this.form.submit()">
                    <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>LAST 7 DAYS</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>LAST 6 MONTHS</option>
                    <option value="year" {{ $filter === 'year' ? 'selected' : '' }}>LAST 5 YEARS</option>
                </select>
            </form>
        </div>
        <div style="height: 250px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="row g-3">
        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="card glass border-secondary rounded-4 overflow-hidden shadow-lg">
                <div class="card-header bg-transparent border-secondary p-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-uppercase italic mb-0">RECENT <span>TRANSACTIONS</span></h6>
                    <a href="{{ route('admin.orders') }}" class="text-warning small text-decoration-none fw-bold">VIEW ALL →</a>
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

                <!-- Disputes -->
                <div class="card glass border-warning rounded-4 shadow-lg">
                    <div class="card-header bg-transparent border-warning p-3">
                        <h6 class="text-warning text-uppercase italic mb-0">ACTIVE <span>DISPUTES</span></h6>
                    </div>
                    <div class="card-body p-3">
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
            initRevenueChart(
                {!! json_encode($salesData->pluck('label')) !!},
                {!! json_encode($salesData->pluck('total')) !!}
            );
        });
    </script>


@endsection

