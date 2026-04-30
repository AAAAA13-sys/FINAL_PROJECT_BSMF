@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="card-title-premium fs-4">PRODUCT <span>MANAGEMENT</span></h1>
        <button type="button" class="btn btn-warning px-3 py-2 rounded-pill fw-black ls-1" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#productModal" onclick="prepareProductModal('add')">+ ADD NEW MODEL</button>
    </div>

    <div class="admin-table-container">
        @if($products->hasPages())
            <div class="admin-pagination-section glass px-4 py-2 d-flex justify-content-between align-items-center" style="border: none; background: rgba(255, 255, 255, 0.05); border-bottom: 1px solid var(--glass-border); border-radius: 0;">
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
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" alt="" style="width: 60px; height: 40px; object-fit: contain; background: #000; border-radius: 8px; margin-right: 1.5rem; border: 1px solid var(--glass-border);">
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
                    <td class="pe-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 edit-product-btn" style="font-size: 0.65rem;" 
                                data-bs-toggle="modal" data-bs-target="#productModal" 
                                data-product='@json($product)'>EDIT</button>
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
    </div>

@push('modals')
<style>
    /* Sleek File Input Styling */
    input[type="file"] {
        font-size: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: transparent; /* Hide browser text */
        padding: 8px;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    input[type="file"]:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--secondary);
    }
    input[type="file"]::file-selector-button {
        background: var(--secondary);
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
    .delete-image-btn:hover {
        background: #ff0000;
        transform: scale(1.1);
    }
</style>
<!-- Universal Product Modal (Floating Card) -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
        <div class="modal-content bg-dark border-secondary shadow-lg" style="border-radius: 12px; border: 1px solid rgba(255,255,255,0.1) !important; pointer-events: auto;">
            <div class="modal-header border-secondary border-opacity-25 py-2 px-3">
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
                            <h6 class="text-secondary text-uppercase italic fw-bold mb-2 pb-1 border-bottom border-secondary border-opacity-25" style="font-size: 0.7rem;">A. DETAILS</h6>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;">Model Name</label>
                            <input type="text" name="name" id="input_name" class="form-control form-control-sm bg-black border-secondary text-white" required style="padding: 4px 10px; font-size: 0.85rem;">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;">Casting</label>
                            <input type="text" name="casting_name" id="input_casting_name" class="form-control form-control-sm bg-black border-secondary text-white" required style="padding: 4px 10px; font-size: 0.85rem;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;">Brand</label>
                            <select name="brand_id" id="input_brand_id" class="form-select form-select-sm bg-black border-secondary text-white" required style="padding: 4px 10px; font-size: 0.85rem;">
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;">Price (₱)</label>
                            <input type="number" step="0.01" name="price" id="input_price" class="form-control form-control-sm bg-black border-secondary text-white" required style="padding: 4px 10px; font-size: 0.85rem;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;">Scale</label>
                            <select name="scale_id" id="input_scale_id" class="form-select form-select-sm bg-black border-secondary text-white" required style="padding: 4px 10px; font-size: 0.85rem;">
                                @foreach($scales as $scale)
                                    <option value="{{ $scale->id }}">{{ $scale->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;">Stock</label>
                            <input type="number" name="stock_quantity" id="input_stock_quantity" class="form-control form-control-sm bg-black border-secondary text-white" required style="padding: 4px 10px; font-size: 0.85rem;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;">Series</label>
                            <select name="series_id" id="input_series_id" class="form-select form-select-sm bg-black border-secondary text-white" style="padding: 4px 10px; font-size: 0.85rem;">
                                <option value="">None</option>
                                @foreach($series as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;">Description</label>
                            <textarea name="description" id="input_description" class="form-control form-control-sm bg-black border-secondary text-white" rows="2" style="padding: 6px 10px; font-size: 0.8rem;"></textarea>
                        </div>

                        <!-- b. Main Image -->
                        <div class="col-md-6 mt-2">
                            <h6 class="text-secondary text-uppercase italic fw-bold mb-1 pb-1 border-bottom border-secondary border-opacity-25" style="font-size: 0.7rem;">B. MAIN IMAGE (1)</h6>
                            <input type="file" name="main_image" id="main_image_input" class="form-control form-control-sm bg-black border-secondary text-white" accept="image/*" onchange="previewImage(this, 'main_preview_container')">
                            <div id="main_preview_container" class="mt-2 d-flex flex-wrap gap-1"></div>
                        </div>

                        <!-- c. Secondary Images -->
                        <div class="col-md-6 mt-2">
                            <h6 class="text-secondary text-uppercase italic fw-bold mb-1 pb-1 border-bottom border-secondary border-opacity-25" style="font-size: 0.7rem;">C. GALLERY (MAX 4)</h6>
                            <input type="file" name="additional_images[]" id="gallery_image_input" class="form-control form-control-sm bg-black border-secondary text-white" accept="image/*" multiple onchange="previewImages(this, 'gallery_preview_container')">
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
    const addBtn = document.querySelector('[onclick*="prepareProductModal(\'add\')"]');
    if (addBtn) {
        addBtn.removeAttribute('onclick'); // Switch to cleaner listener
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
                    <img src="/${product.main_image}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid var(--secondary);">
                    <button type="button" class="delete-image-btn" onclick="deleteMainImage(${product.id})">×</button>
                </div>`;
        }

        // Load Existing Gallery Previews
        if (product.gallery && product.gallery.length > 0) {
            product.gallery.forEach(img => {
                galleryPreview.innerHTML += `
                    <div class="image-preview-wrapper" id="gallery_img_${img.id}">
                        <img src="/${img.image_path}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1);">
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
                    <img src="${e.target.result}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #fff;">
                    <div class="position-absolute top-0 start-0 bg-warning text-black fw-bold px-1" style="font-size: 0.5rem; border-radius: 4px 0 4px 0;">NEW</div>
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
                        <img src="${e.target.result}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #fff;">
                        <div class="position-absolute top-0 start-0 bg-warning text-black fw-bold px-1" style="font-size: 0.4rem; border-radius: 4px 0 4px 0;">NEW</div>
                    </div>`;
            };
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endsection
