@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary mb-2">&larr; Back to Users</a>
            <h2 class="admin-header-title">USER <span>DETAILS</span></h2>
        </div>
        @if(Auth::user()->isAdmin() && Auth::id() !== $user->id)
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Eject this collector from the garage? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-destructive">
                    <i class="fas fa-trash me-2"></i>DELETE ACCOUNT
                </button>
            </form>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="admin-card h-100">
                <h5 class="text-white border-bottom border-secondary pb-2 mb-3">Profile Information</h5>
                <div class="mb-3">
                    <label class="text-muted small">Name</label>
                    <div class="text-white fw-bold fs-5">{{ $user->name }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Username</label>
                    <div><code class="text-light px-2 py-1 bg-darker rounded border border-secondary">{{ $user->username }}</code></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Email Address</label>
                    <div class="text-white">{{ $user->email }}</div>
                    @if($user->hasVerifiedEmail())
                        <span class="badge bg-dark border border-secondary text-white-50 badge-status-verified">VERIFIED</span>
                    @else
                        <span class="badge bg-secondary text-light badge-status-unverified">UNVERIFIED</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Role</label>
                    <div>
                        @if($user->isAdmin())
                            <span class="badge text-white px-3 py-2 badge-admin-red">ADMIN</span>
                        @elseif($user->isStaff())
                            <span class="badge bg-dark px-3 py-2 badge-staff-outline">STAFF</span>
                        @else
                            <span class="badge bg-dark border border-secondary px-3 py-2 text-muted">COLLECTOR</span>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="text-muted small">Joined</label>
                    <div class="text-white-50">{{ $user->created_at->format('F d, Y \a\t H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h5 class="admin-header-title mb-3 admin-header-title-small">ORDER <span>HISTORY</span></h5>
            @if($user->orders->count() > 0)
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                                <th class="pe-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders->sortByDesc('created_at') as $order)
                            <tr>
                                <td class="ps-4"><code class="text-light fw-bold">{{ $order->order_number }}</code></td>
                                <td><span class="text-white-50 small">{{ $order->created_at->format('M d, Y') }}</span></td>
                                <td>
                                    <span class="badge {{ $order->status === 'delivered' ? 'bg-dark border border-secondary text-white' : ($order->status === 'cancelled' ? 'badge-order-status-cancelled' : 'bg-secondary text-light') }} px-2 py-1" style="font-size: 0.65rem;">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </td>
                                <td class="text-white fw-black text-end">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold btn-view-order-sm">VIEW</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="admin-card text-center py-5 text-muted">
                    <i class="fas fa-box-open fs-1 mb-3"></i>
                    <p>This collector has not placed any orders yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
