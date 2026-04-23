@extends('layouts.admin')

@section('content')
<h1 style="margin-bottom: 2rem;">Customer Support Requests</h1>

<div style="display: flex; flex-direction: column; gap: 2rem;">
    @forelse($disputes as $d)
        <div class="glass" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="margin-bottom: 0.5rem; color: var(--secondary);">{{ ucfirst($d->type) }} - Order #{{ $d->order_id }}</h3>
                    <p style="color: var(--text-muted);">From: <strong>{{ $d->user->name }}</strong> on {{ $d->created_at->format('M d, Y') }}</p>
                </div>
                <form action="{{ route('admin.disputes.updateStatus', $d->id) }}" method="POST" style="display: flex; gap: 1rem;">
                    @csrf
                    @php
                        $color = match($d->status) {
                            'pending' => '#fbbf24',
                            'resolved' => '#10b981',
                            'closed' => '#94a3b8',
                            default => 'var(--primary)'
                        };
                    @endphp
                    <select name="status" class="form-control" style="width: auto; padding: 0.5rem; background: rgba(0,0,0,0.3); color: {{ $color }}; border-color: {{ $color }}; font-weight: 800;">
                        <option value="pending" {{ $d->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="resolved" {{ $d->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $d->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; background: {{ $color }}; border-color: {{ $color }};">Update</button>
                </form>
            </div>
            <div style="background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--primary); margin-bottom: 2rem;">
                <p style="white-space: pre-line; color: white;">{{ $d->description }}</p>
            </div>

            <div style="margin-top: 2rem; border-top: 1px solid var(--glass-border); padding-top: 2rem;">
                <h4 style="font-size: 0.8rem; margin-bottom: 1.5rem; text-transform: uppercase; color: var(--text-muted);">Conversation Thread</h4>
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
                    @foreach($d->messages as $msg)
                        <div style="background: {{ $msg->user->role == 'admin' ? 'var(--primary)11' : 'rgba(255,255,255,0.03)' }}; padding: 1rem; border-radius: 12px; border: 1px solid {{ $msg->user->role == 'admin' ? 'var(--primary)33' : 'var(--glass-border)' }}; width: 80%; align-self: {{ $msg->user->role == 'admin' ? 'flex-end' : 'flex-start' }};">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.7rem; color: var(--text-muted);">
                                <span style="font-weight: 800;">{{ $msg->user->role == 'admin' ? 'YOU (Admin)' : $d->user->name }}</span>
                                <span>{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                            <p style="color: white; margin: 0; font-size: 0.9rem;">{{ $msg->message }}</p>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('admin.disputes.reply', $d->id) }}" method="POST">
                    @csrf
                    <div style="display: flex; gap: 1rem;">
                        <textarea name="message" class="form-control" rows="2" placeholder="Write a response..." required></textarea>
                        <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem;">REPLY</button>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <p style="text-align: center; color: var(--text-muted); padding: 5rem 0;">No support requests at the moment.</p>
    @endforelse
</div>
@endsection
