@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <h1 style="font-style: italic; text-transform: uppercase; font-weight: 900; letter-spacing: -1px;">Edit <span>Product</span></h1>
        <a href="{{ route('admin.products') }}" class="btn" style="background: var(--glass); color: white;">Cancel</a>
    </div>

    <div class="auth-container" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" name="image_url" class="form-control" value="{{ $product->image_url }}" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="8">{{ $product->description }}</textarea>
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.5rem; font-size: 1.1rem;">Update Product</button>
            </div>
        </form>
    </div>
</div>
@endsection
