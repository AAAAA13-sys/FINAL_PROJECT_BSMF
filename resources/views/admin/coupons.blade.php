@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="admin-header-title">COUPON <span>VAULT</span></h2>
        @if(Auth::user()->isAdmin())
        <button onclick="document.getElementById('addCouponModal').style.display='flex'" class="btn btn-primary px-4 py-2 rounded-pill fw-black ls-1" style="border: none; box-shadow: 0 4px 15px rgba(128, 12, 31, 0.4);">+ NEW COUPON</button>
        @endif
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="ps-4">Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Usage Depth</th>
                    <th>Expiry Status</th>
                    @if(Auth::user()->isAdmin())
                    <th class="pe-4 text-end">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $coupon)
                <tr>
                    <td class="ps-4">
                        <span class="text-warning fw-black ls-1 fs-5">{{ $coupon->coupon_code }}</span>
                    </td>
                    <td><span class="badge bg-dark border border-secondary text-muted px-3 py-2" style="font-size: 0.6rem; border-radius: 30px;">{{ strtoupper(str_replace('_', ' ', $coupon->discount_type)) }}</span></td>
                    <td>
                        <span class="text-white fw-black">
                            {{ $coupon->discount_type == 'percentage' ? number_format($coupon->discount_value, 0) . '%' : ($coupon->discount_type == 'free_shipping' ? 'FREE SHIP' : '₱' . number_format($coupon->discount_value, 2)) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3" style="min-width: 150px;">
                            <div class="flex-grow-1">
                                <div class="progress" style="height: 6px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                                    @php 
                                        $percent = $coupon->usage_limit ? ($coupon->times_used / $coupon->usage_limit) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-primary" style="width: {{ min($percent, 100) }}%"></div>
                                </div>
                                <div class="text-muted" style="font-size: 0.6rem; margin-top: 6px; font-weight: 700;">{{ $coupon->times_used }} / {{ $coupon->usage_limit ?: '∞' }} BURNED</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($coupon->expires_at && $coupon->expires_at->isPast())
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2" style="font-size: 0.6rem; border-radius: 30px;">EXPIRED</span>
                        @else
                            <span class="text-white-50 small fw-bold">
                                {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'INFINITY' }}
                            </span>
                        @endif
                    </td>
                    @if(Auth::user()->isAdmin())
                    <td class="pe-4 text-end">
                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Burn this coupon code?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" style="font-size: 0.65rem;">DELETE</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Coupon Modal -->
<div id="addCouponModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 5000; justify-content: center; align-items: center; backdrop-filter: blur(15px);">
    <div class="glass p-5 shadow-2xl" style="width: 100%; max-width: 550px; background: var(--bg-darker); border: 2px solid var(--secondary);">
        <h2 class="h4 text-white text-uppercase italic mb-5 fw-black">CREATE <span>PROMO</span></h2>
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Coupon Code</label>
                    <input type="text" name="code" class="form-control bg-dark border-secondary text-white p-3" placeholder="e.g. SPEED20" required>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Type</label>
                    <select name="discount_type" class="form-select bg-dark border-secondary text-white p-3" required>
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (₱)</option>
                        <option value="free_shipping">Free Shipping</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Discount Value</label>
                    <input type="number" step="0.01" name="discount_value" class="form-control bg-dark border-secondary text-white p-3" required>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Min Order (₱)</label>
                    <input type="number" step="0.01" name="min_order_amount" class="form-control bg-dark border-secondary text-white p-3" value="0.00">
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Usage Limit</label>
                    <input type="number" name="usage_limit" class="form-control bg-dark border-secondary text-white p-3" placeholder="Leave empty for unlimited">
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Expiry Date</label>
                    <input type="date" name="expires_at" class="form-control bg-dark border-secondary text-white p-3">
                </div>
            </div>
            <div class="d-flex gap-3 mt-5">
                <button type="submit" class="btn btn-warning flex-grow-1 py-3 fw-black text-uppercase ls-1">ACTIVATE</button>
                <button type="button" onclick="document.getElementById('addCouponModal').style.display='none'" class="btn btn-outline-secondary flex-grow-1 py-3 fw-bold text-uppercase">CANCEL</button>
            </div>
        </form>
    </div>
</div>
@endsection
