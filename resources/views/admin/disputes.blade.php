@extends('layouts.admin')

@section('content')
<div class="fade-in p-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h3 text-white text-uppercase italic fw-black">INTERVENTION <span style="color: var(--secondary);">TERMINAL</span></h1>
    </div>

    <div class="row g-4">
        @forelse($disputes as $d)
            <div class="col-12">
                <div class="card glass border-secondary rounded-4 shadow-lg overflow-hidden">
                    <div class="row g-0">
                        <!-- Left Panel: Info -->
                        <div class="col-md-4 border-end border-secondary" style="background: rgba(0,0,0,0.2);">
                            <div class="p-4">
                                <div class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-black ls-1">
                                    {{ strtoupper($d->status) }}
                                </div>
                                <h3 class="h4 text-white mb-2 text-uppercase italic fw-black">{{ str_replace('_', ' ', $d->subject) }}</h3>
                                <p class="text-secondary small fw-bold mb-4 ls-1">#{{ $d->dispute_number }}</p>
                                
                                <div class="bg-dark bg-opacity-50 p-4 rounded-4 border border-secondary border-opacity-25 mb-4">
                                    <h6 class="text-muted small text-uppercase fw-black ls-2 mb-3">Collector Info</h6>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="user-avatar" style="width: 40px; height: 40px;">{{ substr($d->user->name, 0, 1) }}</div>
                                        <div>
                                            <div class="text-white small fw-bold">{{ $d->user->name }}</div>
                                            <div class="text-muted" style="font-size: 0.65rem;">{{ $d->user->email }}</div>
                                        </div>
                                    </div>
                                    <hr class="border-secondary opacity-25">
                                    <h6 class="text-muted small text-uppercase fw-black ls-2 mb-2">Related Order</h6>
                                    <a href="{{ route('admin.orders.show', $d->order->id) }}" class="text-warning text-decoration-none small fw-bold">#{{ $d->order->order_number }} →</a>
                                </div>

                                <form action="{{ route('admin.disputes.updateStatus', $d->id) }}" method="POST">
                                    @csrf
                                    <label class="form-label text-muted small fw-black ls-1 text-uppercase">Status Control</label>
                                    <div class="input-group">
                                        <select name="status" class="form-select bg-dark border-secondary text-white small">
                                            <option value="pending" {{ $d->status === 'pending' ? 'selected' : '' }}>PENDING</option>
                                            <option value="resolved" {{ $d->status === 'resolved' ? 'selected' : '' }}>RESOLVED</option>
                                            <option value="closed" {{ $d->status === 'closed' ? 'selected' : '' }}>CLOSED</option>
                                        </select>
                                        <button type="submit" class="btn btn-warning px-3"><i class="fas fa-check"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Right Panel: Conversation -->
                        <div class="col-md-8">
                            <div class="p-4 h-100 d-flex flex-column">
                                <div class="bg-black bg-opacity-50 p-4 border-start border-warning border-4 rounded-3 mb-5">
                                    <h6 class="text-warning small text-uppercase fw-black ls-2 mb-2">Original Complaint</h6>
                                    <p class="text-white mb-0" style="white-space: pre-line; font-size: 0.95rem; line-height: 1.6; opacity: 0.9;">{{ $d->description }}</p>
                                </div>

                                <h6 class="text-muted small text-uppercase fw-black ls-2 mb-4">Transmission Thread</h6>
                                <div class="d-flex flex-column gap-3 mb-5 flex-grow-1" style="max-height: 400px; overflow-y: auto; padding-right: 1rem;">
                                    @foreach($d->messages as $msg)
                                        <div class="p-3 rounded-4 border {{ $msg->user->is_admin ? 'bg-primary bg-opacity-10 border-primary border-opacity-25 align-self-end' : 'bg-dark border-secondary align-self-start' }}" style="max-width: 80%;">
                                            <div class="d-flex justify-content-between gap-4 mb-2 small">
                                                <span class="fw-bold {{ $msg->user->is_admin ? 'text-primary' : 'text-white' }}" style="font-size: 0.65rem;">{{ $msg->user->is_admin ? 'SUPPORT AGENT' : strtoupper($d->user->name) }}</span>
                                                <span class="text-muted italic" style="font-size: 0.6rem;">{{ $msg->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-white mb-0" style="font-size: 0.85rem;">{{ $msg->message }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-auto">
                                    <form action="{{ route('admin.disputes.reply', $d->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <textarea name="message" class="form-control bg-dark border-secondary text-white" rows="2" placeholder="Type transmission back to collector..." required></textarea>
                                            <button type="submit" class="btn btn-warning px-4 fw-black ls-1">SEND REPLY</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-handshake text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                <h3 class="text-muted italic h5">Terminal Clear. No Active Conflicts.</h3>
            </div>
        @endforelse
    </div>
</div>
@endsection
