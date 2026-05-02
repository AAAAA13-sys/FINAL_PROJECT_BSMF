@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="admin-header-title">PRODUCT <span>MANAGEMENT</span></h2>
        @if(Auth::user()->isAdmin())
        <button type="button" class="btn-bsmf-primary" data-bs-toggle="modal" data-bs-target="#productModal" id="addNewModelBtn">+ ADD NEW MODEL</button>
        @endif
    </div>

    <div class="admin-table-container">
        @if($products->hasPages())
            <div class="glass-pagination">
                <div class="small text-muted italic" style="font-size: 0.65rem;">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                </div>
                <div class="admin-pagination-links">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="ps-4">Model Info</th>
                    <th>Casting</th>
                    <th>Price</th>
                    <th>Stock</th>
                    @if(Auth::user()->isAdmin())
                    <th class="pe-4 text-end">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" alt="" class="product-thumbnail-sm me-3">
                            <div>
                                <div class="text-white fw-bold">{{ $product->name }}</div>
                                <div class="text-muted small text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">{{ $product->brand->name ?? 'Generic' }} • {{ $product->scale->name ?? '1:64' }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="text-white-50 small">{{ $product->casting_name ?? 'N/A' }}</span></td>
                    <td class="text-warning fw-black">₱{{ number_format($product->price, 2) }}</td>
                    <td>
                        <span class="fw-bold {{ $product->stock_quantity < 10 ? 'text-danger' : 'text-success' }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </td>
                    @if(Auth::user()->isAdmin())
                    <td class="pe-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn-bsmf-outline edit-product-btn" 
                                data-bs-toggle="modal" data-bs-target="#productModal" 
                                data-product='@json($product)'>EDIT</button>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this grail from the garage?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-bsmf-outline border-danger text-danger">DELETE</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@push('modals')
<style>
    /* Sleek File Input Styling - Left in style block as it's highly specific to this page's custom file inputs */
    input[type="file"] {
        font-size: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: transparent; 
        padding: 8px;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    input[type="file"]:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--secondary-blue);
    }
    input[type="file"]::file-selector-button {
        background: var(--secondary-blue);
        color: #000;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.7rem;
        margin-right: 10px;
        cursor: pointer;
    }
    
    .image-preview-wrapper {
        position: relative;
        display: inline-block;
    }
    .delete-image-btn {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--danger);
        color: white;
        border: none;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        font-size: 10px;
        line-height: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.5);
        z-index: 10;
    }
</style>
<!-- Universal Product Modal (Floating Card) -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
        <div class="modal-content bsmf-modal-content">
            <div class="modal-header bsmf-modal-header">
                <h6 class="modal-title text-white text-uppercase italic fw-black mb-0" id="productModalLabel" style="font-size: 0.9rem;">NEW <span>ACQUISITION</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.6rem;"></button>
            </div>
            <div class="modal-body p-3">
                <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="methodField"></div>
                    
                    <div class="row g-2">
                        <!-- a. Current Details -->
                        <div class="col-md-12">
                            <h6 class="section-divider">A. DETAILS</h6>
                        </div>
                        <div class="col-md-12">
                            <label class="input-label-sm">Model Name</label>
                            <input type="text" name="name" id="input_name" class="form-control garage-input" required>
                        </div>
                        <div class="col-md-12">
                            <label class="input-label-sm">Casting</label>
                            <input type="text" name="casting_name" id="input_casting_name" class="form-control garage-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label-sm">Brand</label>
                            <select name="brand_id" id="input_brand_id" class="form-select garage-select" required>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label-sm">Price (₱)</label>
                            <input type="number" step="0.01" min="0" name="price" id="input_price" class="form-control garage-input" required>
                        </div>
                        <div class="col-md-4">
                            <label class="input-label-sm">Scale</label>
                            <select name="scale_id" id="input_scale_id" class="form-select garage-select" required>
                                @foreach($scales as $scale)
                                    <option value="{{ $scale->id }}">{{ $scale->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="input-label-sm">Stock</label>
                            <input type="number" min="0" name="stock_quantity" id="input_stock_quantity" class="form-control garage-input" required>
                        </div>
                        <div class="col-md-4">
                            <label class="input-label-sm">Series</label>
                            <select name="series_id" id="input_series_id" class="form-select garage-select">
                                <option value="">None</option>
                                @foreach($series as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="input-label-sm">Description</label>
                            <textarea name="description" id="input_description" class="form-control garage-textarea" rows="2"></textarea>
                        </div>

                        <!-- b. Main Image -->
                        <div class="col-md-6 mt-2">
                            <h6 class="section-divider">B. MAIN IMAGE (1)</h6>
                            <input type="file" name="main_image" id="main_image_input" class="form-control garage-input" accept="image/*" onchange="previewImage(this, 'main_preview_container')">
                            <div id="main_preview_container" class="mt-2 d-flex flex-wrap gap-1"></div>
                        </div>

                        <!-- c. Secondary Images -->
                        <div class="col-md-6 mt-2">
                            <h6 class="section-divider">C. GALLERY (MAX 4)</h6>
                            <input type="file" name="additional_images[]" id="gallery_image_input" class="form-control garage-input" accept="image/*" multiple onchange="previewImages(this, 'gallery_preview_container')">
                            <div id="gallery_preview_container" class="mt-2 d-flex flex-wrap gap-1"></div>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-2 border-top border-secondary border-opacity-25">
                        <button type="submit" id="submitBtn" class="btn btn-warning w-100 py-1 fw-black text-uppercase ls-1" style="font-size: 0.7rem; border-radius: 8px;">ADD TO GARAGE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Handle Edit Button Clicks
    const editBtns = document.querySelectorAll('.edit-product-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const product = JSON.parse(btn.getAttribute('data-product'));
            prepareProductModal('edit', product);
        });
    });

    // Handle Add Button Click (Reset form)
    const addBtn = document.getElementById('addNewModelBtn');
    if (addBtn) {
        addBtn.addEventListener('click', () => prepareProductModal('add'));
    }
});

function prepareProductModal(mode, product = null) {
    const form = document.getElementById('productForm');
    const label = document.getElementById('productModalLabel');
    const submitBtn = document.getElementById('submitBtn');
    const methodField = document.getElementById('methodField');
    const mainPreview = document.getElementById('main_preview_container');
    const galleryPreview = document.getElementById('gallery_preview_container');

    // Reset Form & Previews
    form.reset();
    methodField.innerHTML = '';
    mainPreview.innerHTML = '';
    galleryPreview.innerHTML = '';

    if (mode === 'add') {
        label.innerHTML = 'NEW <span>ACQUISITION</span>';
        submitBtn.innerHTML = 'ADD TO GARAGE';
        form.action = "{{ route('admin.products.store') }}";
    } else {
        label.innerHTML = 'EDIT <span>COLLECTIBLE</span>';
        submitBtn.innerHTML = 'UPDATE IN GARAGE';
        form.action = `/admin/products/${product.id}`;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Fill fields
        document.getElementById('input_name').value = product.name || '';
        document.getElementById('input_casting_name').value = product.casting_name || '';
        document.getElementById('input_brand_id').value = product.brand_id || '';
        document.getElementById('input_scale_id').value = product.scale_id || '';
        document.getElementById('input_series_id').value = product.series_id || '';
        document.getElementById('input_price').value = product.price || '';
        document.getElementById('input_stock_quantity').value = product.stock_quantity || '';
        document.getElementById('input_description').value = product.description || '';

        // Load Existing Main Image Preview
        if (product.main_image) {
            mainPreview.innerHTML = `
                <div class="image-preview-wrapper" id="main_img_container">
                    <img src="/${product.main_image}" class="image-preview-square">
                    <button type="button" class="delete-image-btn" onclick="deleteMainImage(${product.id})">×</button>
                </div>`;
        }

        // Load Existing Gallery Previews
        if (product.gallery && product.gallery.length > 0) {
            product.gallery.forEach(img => {
                galleryPreview.innerHTML += `
                    <div class="image-preview-wrapper" id="gallery_img_${img.id}">
                        <img src="/${img.image_path}" class="image-preview-square">
                        <button type="button" class="delete-image-btn" onclick="deleteGalleryImage(${img.id})">×</button>
                    </div>`;
            });
        }
    }
}

function deleteGalleryImage(id) {
    if (confirm('Permanently remove this image from the gallery?')) {
        const wrapper = document.getElementById(`gallery_img_${id}`);
        wrapper.style.opacity = '0.5';
        wrapper.style.pointerEvents = 'none';

        fetch(`/admin/products/images/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok && data.success) {
                wrapper.remove();
            } else {
                alert('Error: ' + (data.message || 'Could not delete image'));
                wrapper.style.opacity = '1';
                wrapper.style.pointerEvents = 'auto';
            }
        })
        .catch(err => {
            console.error('Delete Error:', err);
            alert('Critical Error: Failed to reach the server.');
            wrapper.style.opacity = '1';
            wrapper.style.pointerEvents = 'auto';
        });
    }
}

function deleteMainImage(productId) {
    if (confirm('Remove the primary display image?')) {
        const wrapper = document.getElementById('main_img_container');
        wrapper.style.opacity = '0.5';
        wrapper.style.pointerEvents = 'none';

        fetch(`/admin/products/${productId}/main-image`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok && data.success) {
                wrapper.remove();
            } else {
                alert('Error: ' + (data.message || 'Could not delete main image'));
                wrapper.style.opacity = '1';
                wrapper.style.pointerEvents = 'auto';
            }
        })
        .catch(err => {
            console.error('Delete Error:', err);
            alert('Critical Error: Failed to reach the server.');
            wrapper.style.opacity = '1';
            wrapper.style.pointerEvents = 'auto';
        });
    }
}

// Live Preview for Main Image
function previewImage(input, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = ''; // Clear existing
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            container.innerHTML = `
                <div class="image-preview-wrapper">
                    <img src="${e.target.result}" class="image-preview-square border-white">
                    <div class="badge-new-pill">NEW</div>
                </div>`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Live Preview for Multiple Images
function previewImages(input, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = ''; // Clear existing
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                container.innerHTML += `
                    <div class="image-preview-wrapper">
                        <img src="${e.target.result}" class="image-preview-square border-white">
                        <div class="badge-new-pill">NEW</div>
                    </div>`;
            };
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endsection
