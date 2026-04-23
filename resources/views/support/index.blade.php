@extends('layouts.app')

@section('title', 'Customer Support - BFMSL')

@section('content')
<div style="padding: 5rem 5%;">
    <div class="auth-container glass fade-in" style="max-width: 600px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Customer Support</h2>

        <form action="{{ route('support.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Related Order</label>
                <select name="order_id" class="form-control" required>
                    <option value="">Select an order</option>
                    @foreach ($orders as $order)
                        <option value="{{ $order->id }}" {{ $order_id == $order->id ? 'selected' : '' }}>
                            Order #{{ $order->id }} ({{ $order->created_at->format('M d, Y') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Issue Type</label>
                <select name="type" class="form-control" required>
                    <option value="wrong item">Wrong item received</option>
                    <option value="never received">Never received</option>
                    <option value="damaged product">Damaged product</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description of Issue</label>
                <textarea name="description" class="form-control" rows="5" required placeholder="Please provide details about your issue..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Submit Request</button>
        </form>
    </div>

    @if ($disputes->count() > 0)
    <div style="max-width: 800px; margin: 4rem auto 0;">
        <h3 style="margin-bottom: 2rem;">Your Support Requests</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach ($disputes as $dispute)
                <div class="glass" style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="font-weight: 600; color: white;">#{{ $dispute->order_id }} - {{ ucfirst($dispute->type) }}</p>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">{{ $dispute->created_at->format('M d, Y') }}</p>
                    </div>
                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; background: var(--glass); border: 1px solid {{ $dispute->status === 'pending' ? 'var(--primary)' : 'var(--accent)' }}; color: white;">
                        {{ ucfirst($dispute->status) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
