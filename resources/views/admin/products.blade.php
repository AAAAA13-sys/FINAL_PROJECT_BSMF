@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <h1 style="font-style: italic; text-transform: uppercase; font-weight: 900; letter-spacing: -1px;">Product <span>Management</span></h1>
        <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary" style="padding: 1rem 2rem; border-radius: 12px;">+ Add New Product</button>
    </div>

    <div class="glass" style="padding: 1rem; border-radius: 16px; overflow: hidden;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td style="color: var(--text-muted);">{{ $product->id }}</td>
                    <td>
                        <img src="{{ asset($product->image_url) }}" alt="" style="width: 60px; height: 40px; object-fit: contain; background: white; border-radius: 8px; border: 1px solid var(--glass-border);">
                    </td>
                    <td style="font-weight: 800; text-transform: uppercase;">{{ $product->name }}</td>
                    <td><span style="background: rgba(255,255,255,0.05); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem;">{{ $product->category->name ?? 'None' }}</span></td>
                    <td style="color: var(--secondary); font-weight: 900;">${{ number_format($product->price, 2) }}</td>
                    <td>
                        <span style="color: {{ $product->stock < 10 ? 'var(--danger)' : 'white' }}; font-weight: 800;">{{ $product->stock }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" style="color: var(--secondary); margin-right: 1.5rem; text-decoration: none; font-weight: 800; font-size: 0.8rem; text-transform: uppercase;">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')" style="display: inline;">
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

<!-- Add Modal -->
<div id="addModal" class="auth-container" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 500px; z-index: 2000;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-style: italic; text-transform: uppercase;">New <span>Product</span></h2>
        <button onclick="document.getElementById('addModal').style.display='none'" style="background: none; border: none; color: white; cursor: pointer;"><i class="fas fa-times"></i></button>
    </div>
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label>Image URL (e.g., images/car.webp)</label>
            <input type="text" name="image_url" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">Save Product</button>
        </div>
    </form>
</div>
@endsection
