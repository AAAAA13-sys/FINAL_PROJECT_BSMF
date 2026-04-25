@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="card-title-premium fs-2">USER <span>MANAGEMENT</span></h1>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="ps-4">Collector Info</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="ps-4">
                        <div class="text-white fw-bold">{{ $user->name }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">{{ $user->email }}</div>
                    </td>
                    <td><code class="text-warning small fw-bold">{{ $user->username }}</code></td>
                    <td>
                        @if($user->is_admin)
                            <span class="badge bg-primary text-white px-3 py-2" style="font-size: 0.6rem; border-radius: 30px;">ADMIN</span>
                        @else
                            <span class="badge bg-dark border border-secondary text-muted px-3 py-2" style="font-size: 0.6rem; border-radius: 30px;">COLLECTOR</span>
                        @endif
                    </td>
                    <td><span class="text-white-50 small">{{ $user->created_at->format('M d, Y') }}</span></td>
                    <td class="pe-4 text-end">
                        @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Eject this collector from the garage? This cannot be undone.')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" style="font-size: 0.65rem;">DELETE</button>
                            </form>
                        @else
                            <span class="badge bg-secondary text-dark px-3 py-2" style="font-size: 0.6rem; border-radius: 30px;">YOU</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($users->hasPages())
            <div class="p-4 border-top border-secondary bg-darker bg-opacity-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
