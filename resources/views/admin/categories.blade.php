@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <h1 style="font-style: italic; text-transform: uppercase; font-weight: 900; letter-spacing: -1px;">Category <span>Management</span></h1>
        <button onclick="document.getElementById('addCategoryModal').style.display='flex'" class="btn btn-primary">+ Add New Category</button>
    </div>

    <div class="glass" style="padding: 1rem; border-radius: 16px; overflow: hidden;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Products Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td style="color: var(--text-muted);">{{ $category->id }}</td>
                    <td style="font-weight: 800; text-transform: uppercase;">{{ $category->name }}</td>
                    <td>
                        <span style="background: rgba(255,255,255,0.05); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem;">
                            {{ $category->products_count }} Models
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: var(--danger); background: none; border: none; cursor: pointer; font-weight: 800; font-size: 0.8rem; text-transform: uppercase;" {{ $category->products_count > 0 ? 'disabled style=opacity:0.3;cursor:not-allowed' : '' }}>Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(10px);">
    <div class="auth-container" style="max-width: 500px; width: 90%;">
        <h2 class="auth-title">ADD <span>CATEGORY</span></h2>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Vintage Racing" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Create Category</button>
                <button type="button" onclick="document.getElementById('addCategoryModal').style.display='none'" class="btn" style="flex: 1; background: var(--glass); color: white;">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
