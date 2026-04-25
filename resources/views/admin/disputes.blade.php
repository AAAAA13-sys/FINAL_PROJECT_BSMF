@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h2 text-white text-uppercase italic fw-bold">SUPPORT <span>DISPATCH</span></h1>
    </div>

    <div class="d-flex flex-column gap-5">
        @forelse($disputes as $d)
            <div class="card border-secondary rounded-4 shadow-lg overflow-hidden">
                <div class="card-header bg-darker border-secondary p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="h5 text-warning mb-1 text-uppercase italic fw-bold">{{ str_replace('_', ' ', $d->dispute_type) }}</h3>
                        <p class="text-muted small mb-0">ORDER <span class="text-white">#{{ $d->order->order_number }}</span> • FROM <span class="text-white">{{ $d->user->name }}</span> • {{ $d->created_at->format('M d, Y') }}</p>
                    </div>
                    <form action="{{ route('admin.disputes.updateStatus', $d->id) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white w-auto">
                            <option value="pending" {{ $d->status === 'pending' ? 'selected' : '' }}>PENDING</option>
                            <option value="resolved" {{ $d->status === 'resolved' ? 'selected' : '' }}>RESOLVED</option>
                            <option value="closed" {{ $d->status === 'closed' ? 'selected' : '' }}>CLOSED</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary px-3">UPDATE</button>
                    </form>
                </div>
                <div class="card-body p-4">
                    <div class="bg-black bg-opacity-50 p-4 border-start border-primary border-4 rounded-3 mb-5">
                        <p class="text-white mb-0" style="white-space: pre-line;">{{ $d->description }}</p>
                    </div>

                    <div class="border-top border-secondary pt-4">
                        <h6 class="text-muted small text-uppercase fw-bold mb-4">Conversation Thread</h6>
                        <div class="d-flex flex-column gap-3 mb-4">
                            @foreach($d->messages as $msg)
                                <div class="p-3 rounded-4 border {{ $msg->user->is_admin ? 'bg-primary bg-opacity-10 border-primary border-opacity-25 align-self-end' : 'bg-dark border-secondary align-self-start' }}" style="max-width: 80%;">
                                    <div class="d-flex justify-content-between gap-4 mb-2 small">
                                        <span class="fw-bold {{ $msg->user->is_admin ? 'text-primary' : 'text-white' }}">{{ $msg->user->is_admin ? 'GARAGE SUPPORT' : $d->user->name }}</span>
                                        <span class="text-muted italic">{{ $msg->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-white mb-0 small">{{ $msg->message }}</p>
                                </div>
                            @endforeach
                        </div>

                        <form action="{{ route('admin.disputes.reply', $d->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <textarea name="message" class="form-control bg-dark border-secondary text-white" rows="2" placeholder="Type your response to the collector..." required></textarea>
                                <button type="submit" class="btn btn-primary px-4 fw-bold">REPLY</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-check-circle text-muted mb-3" style="font-size: 3rem;"></i>
                <p class="text-muted italic">The garage is quiet. No active disputes.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
