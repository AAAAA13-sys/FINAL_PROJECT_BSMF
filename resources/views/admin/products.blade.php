@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="card-title-premium fs-2">PRODUCT <span>MANAGEMENT</span></h1>
        <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-warning px-4 py-2 rounded-pill fw-black ls-1">+ ADD NEW MODEL</button>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="ps-4">Model Info</th>
                    <th>Casting</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <img src="{{ $product->main_image ?? asset('images/placeholder-car.webp') }}" alt="" style="width: 60px; height: 40px; object-fit: contain; background: #000; border-radius: 8px; margin-right: 1.5rem; border: 1px solid var(--glass-border);">
                            <div>
                                <div class="text-white fw-bold">{{ $product->name }}</div>
                                <div class="text-muted small text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">{{ $product->brand->name ?? 'Generic' }} • {{ $product->scale->name ?? '1:64' }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="text-white-50 small">{{ $product->casting_name ?? 'N/A' }}</span></td>
                    <td><span class="badge bg-dark border border-secondary text-muted px-3 py-2" style="font-size: 0.6rem;">{{ $product->category->name ?? 'Uncategorized' }}</span></td>
                    <td class="text-warning fw-black">${{ number_format($product->price, 2) }}</td>
                    <td>
                        <span class="fw-bold {{ $product->stock_quantity < 10 ? 'text-danger' : 'text-success' }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3" style="font-size: 0.65rem;">EDIT</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this grail from the garage?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="font-size: 0.65rem;">DELETE</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($products->hasPages())
            <div class="p-4 border-top border-secondary bg-darker bg-opacity-50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Modal (Simplified Bootstrap Modal feel using custom container) -->
<div id="addModal" class="glass shadow-2xl p-0 overflow-hidden" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 700px; z-index: 2000; border: 2px solid var(--secondary); max-height: 90vh; overflow-y: auto;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <h2 class="h4 text-white text-uppercase italic mb-0">NEW <span>ACQUISITION</span></h2>
        <button onclick="document.getElementById('addModal').style.display='none'" class="btn-close btn-close-white"></button>
    </div>
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label text-muted small fw-bold text-uppercase ls-1">Model Display Name</label>
                <input type="text" name="name" class="form-control bg-dark border-secondary text-white" placeholder="e.g. '67 Camaro STH" required>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small fw-bold text-uppercase ls-1">Casting Name</label>
                <input type="text" name="casting_name" class="form-control bg-dark border-secondary text-white" placeholder="e.g. '67 Chevrolet Camaro" required>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small fw-bold text-uppercase ls-1">Brand</label>
                <select name="brand_id" class="form-select bg-dark border-secondary text-white" required>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small fw-bold text-uppercase ls-1">Scale</label>
                <select name="scale_id" class="form-select bg-dark border-secondary text-white" required>
                    @foreach($scales as $scale)
                        <option value="{{ $scale->id }}">{{ $scale->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small fw-bold text-uppercase ls-1">Price ($)</label>
                <input type="number" step="0.01" name="price" class="form-control bg-dark border-secondary text-white" required>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small fw-bold text-uppercase ls-1">Stock</label>
                <input type="number" name="stock_quantity" class="form-control bg-dark border-secondary text-white" required>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small fw-bold text-uppercase ls-1">Series</label>
                <select name="series_id" class="form-select bg-dark border-secondary text-white">
                    <option value="">None</option>
                    @foreach($series as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small fw-bold text-uppercase ls-1">Category</label>
                <select name="category_id" class="form-select bg-dark border-secondary text-white">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label text-muted small fw-bold text-uppercase ls-1">Description</label>
                <textarea name="description" class="form-control bg-dark border-secondary text-white" rows="3"></textarea>
            </div>
        </div>
        <div class="mt-5">
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase ls-2">ADD TO GARAGE</button>
        </div>
    </form>
</div>
@endsection
