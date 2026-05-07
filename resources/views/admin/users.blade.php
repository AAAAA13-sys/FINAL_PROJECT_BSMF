@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="admin-header-title">USER <span>MANAGEMENT</span></h2>
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
                        <div class="text-muted fs-07rem">{{ $user->email }}</div>
                    </td>
                    <td><code class="text-light px-2 py-1 bg-dark rounded border border-secondary small fw-bold">{{ $user->username }}</code></td>
                    <td>
                        @if($user->isAdmin())
                            <span class="badge bg-danger text-white px-3 py-2 fw-black italic">ADMIN</span>
                        @elseif($user->isStaff())
                            <span class="badge bg-warning text-dark px-3 py-2 fw-black italic">STAFF</span>
                        @elseif(!$user->hasVerifiedEmail())
                            <span class="badge bg-dark text-warning border border-warning px-3 py-2 fw-black italic">PENDING OTP</span>
                        @else
                            <span class="badge bg-info text-dark px-3 py-2 fw-black italic">COLLECTOR</span>
                        @endif
                    </td>
                    <td><span class="text-white-50 small">{{ $user->created_at->format('M d, Y') }}</span></td>
                    <td class="pe-4 text-end">
                        @if($user->id === Auth::id())
                            <span class="badge badge-current-user">YOU</span>
                        @elseif(Auth::user()->isAdmin())
                            <!-- PROMOTE BUTTON -->
                            @if($user->role === 'admin')
                                <button class="btn btn-admin-disabled me-1" disabled>PROMOTE</button>
                            @else
                                <form action="{{ route('admin.users.promote', $user->id) }}" method="POST" class="d-inline me-1" onsubmit="return confirm('Promote this collector to Staff?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-admin-promote">PROMOTE</button>
                                </form>
                            @endif

                            <!-- DEMOTE BUTTON -->
                            @if($user->role === 'customer' || $user->role === 'admin')
                                <button class="btn btn-admin-disabled me-1" disabled>DEMOTE</button>
                            @else
                                <form action="{{ route('admin.users.demote', $user->id) }}" method="POST" class="d-inline me-1" onsubmit="return confirm('Demote this staff member to Collector?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-admin-demote">DEMOTE</button>
                                </form>
                            @endif

                            <!-- DETAILS BUTTON -->
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-admin-details">DETAILS</a>
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
