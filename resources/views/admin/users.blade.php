@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <h1 style="margin-bottom: 3rem; font-style: italic; text-transform: uppercase; font-weight: 900; letter-spacing: -1px;">User <span>Management</span></h1>

    <div class="glass" style="padding: 1rem; border-radius: 16px; overflow: hidden;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td style="color: var(--text-muted);">{{ $user->id }}</td>
                    <td style="font-weight: 800; text-transform: uppercase;">{{ $user->name }}</td>
                    <td style="color: #60a5fa;">{{ $user->email }}</td>
                    <td>
                        <span style="background: {{ $user->role === 'admin' ? 'var(--primary)1a' : 'rgba(255,255,255,0.05)' }}; 
                                     color: {{ $user->role === 'admin' ? 'var(--primary)' : 'white' }}; 
                                     padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; border: 1px solid {{ $user->role === 'admin' ? 'var(--primary)' : 'var(--glass-border)' }}; font-weight: 800;">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user? This action cannot be undone.')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: var(--danger); background: none; border: none; cursor: pointer; font-weight: 800; font-size: 0.8rem; text-transform: uppercase;">Delete</button>
                            </form>
                        @else
                            <span style="color: var(--text-muted); font-size: 0.8rem; font-style: italic;">YOU</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
