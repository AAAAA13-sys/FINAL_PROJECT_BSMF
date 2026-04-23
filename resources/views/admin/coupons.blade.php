@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <h1 style="font-style: italic; text-transform: uppercase; font-weight: 900; letter-spacing: -1px;">Coupon <span>Management</span></h1>
        <button onclick="document.getElementById('addCouponModal').style.display='flex'" class="btn btn-primary">+ Create New Coupon</button>
    </div>

    <div class="glass" style="padding: 1rem; border-radius: 16px; overflow: hidden;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Usage</th>
                    <th>Expires</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $coupon)
                <tr>
                    <td style="font-weight: 800; color: var(--secondary);">{{ $coupon->code }}</td>
                    <td style="text-transform: uppercase;">{{ $coupon->type }}</td>
                    <td style="font-weight: 800;">
                        {{ $coupon->type == 'percent' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}
                    </td>
                    <td>
                        <div style="font-size: 0.8rem;">
                            {{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }}
                        </div>
                        <div style="width: 100px; height: 4px; background: rgba(255,255,255,0.05); margin-top: 5px; border-radius: 2px; overflow: hidden;">
                            @php 
                                $percent = $coupon->usage_limit ? ($coupon->used_count / $coupon->usage_limit) * 100 : 0;
                            @endphp
                            <div style="width: {{ min($percent, 100) }}%; height: 100%; background: var(--primary);"></div>
                        </div>
                    </td>
                    <td style="color: {{ $coupon->expires_at && $coupon->expires_at < now() ? 'var(--danger)' : 'white' }}">
                        {{ $coupon->expires_at ? date('M d, Y', strtotime($coupon->expires_at)) : 'Never' }}
                    </td>
                    <td>
                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Delete this coupon?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: var(--danger); background: none; border: none; cursor: pointer; font-weight: 800; font-size: 0.8rem; text-transform: uppercase;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Coupon Modal -->
<div id="addCouponModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(10px);">
    <div class="auth-container" style="max-width: 600px; width: 90%;">
        <h2 class="auth-title">CREATE <span>COUPON</span></h2>
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="form-group">
                    <label>Coupon Code</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g. SPEED20" required>
                </div>
                <div class="form-group">
                    <label>Discount Type</label>
                    <select name="type" class="form-control" required>
                        <option value="percent">Percentage (%)</option>
                        <option value="fixed">Fixed Amount ($)</option>
                    </select>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="form-group">
                    <label>Discount Value</label>
                    <input type="number" step="0.01" name="value" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Usage Limit (Optional)</label>
                    <input type="number" name="usage_limit" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label>Expiry Date (Optional)</label>
                <input type="date" name="expires_at" class="form-control">
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Create Coupon</button>
                <button type="button" onclick="document.getElementById('addCouponModal').style.display='none'" class="btn" style="flex: 1; background: var(--glass); color: white;">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
