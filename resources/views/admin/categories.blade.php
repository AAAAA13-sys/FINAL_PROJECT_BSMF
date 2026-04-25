@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="card-title-premium fs-2">CATEGORY <span>MANAGEMENT</span></h1>
        <button onclick="document.getElementById('addCategoryModal').style.display='flex'" class="btn btn-warning px-4 py-2 rounded-pill fw-black ls-1">+ NEW CATEGORY</button>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Models Count</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td class="ps-4 text-muted small">#{{ $category->id }}</td>
                    <td><span class="text-white fw-bold text-uppercase">{{ $category->name }}</span></td>
                    <td><code class="text-warning small fw-bold">{{ $category->slug }}</code></td>
                    <td>
                        <span class="badge bg-dark border border-secondary text-muted px-3 py-2" style="font-size: 0.6rem; border-radius: 30px;">
                            {{ $category->products_count }} MODELS
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this collection?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" style="font-size: 0.65rem;" {{ $category->products_count > 0 ? 'disabled title="Cannot delete category with products"' : '' }}>
                                DELETE
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 5000; justify-content: center; align-items: center; backdrop-filter: blur(15px);">
    <div class="glass p-5 shadow-2xl" style="width: 100%; max-width: 450px; background: var(--bg-darker); border: 2px solid var(--secondary);">
        <h2 class="h4 text-white text-uppercase italic mb-4 fw-black">CREATE <span>COLLECTION</span></h2>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-group mb-5">
                <label class="text-muted small fw-bold text-uppercase mb-3 d-block ls-1">Collection Name</label>
                <input type="text" name="name" class="form-control bg-dark border-secondary text-white p-3" placeholder="e.g. Vintage Racing" required>
            </div>
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-warning flex-grow-1 py-3 fw-black text-uppercase ls-1">CREATE</button>
                <button type="button" onclick="document.getElementById('addCategoryModal').style.display='none'" class="btn btn-outline-secondary flex-grow-1 py-3 fw-bold text-uppercase">CANCEL</button>
            </div>
        </form>
    </div>
</div>
@endsection
