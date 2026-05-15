@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="admin-header-title">PRODUCT <span>MANAGEMENT</span></h2>
        @if(Auth::user()->isAdmin())
        <button type="button" class="btn-bsmf-primary" data-bs-toggle="modal" data-bs-target="#productModal" id="addNewModelBtn">+ ADD NEW MODEL</button>
        @endif
    </div>

    <div class="admin-card p-3 mb-4 border-secondary border-opacity-10 bg-darker">
        <form action="{{ route('admin.products') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="text-muted smaller text-uppercase ls-1 mb-2 d-block">Search</label>
                <input type="text" name="search" class="form-control garage-input py-2" placeholder="Name or casting..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="text-muted smaller text-uppercase ls-1 mb-2 d-block">Series</label>
                <select name="series_id" class="form-select garage-select py-2" onchange="this.form.submit()">
                    <option value="">ALL SERIES</option>
                    @foreach($series as $s)
                        <option value="{{ $s->id }}" {{ request('series_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="text-muted smaller text-uppercase ls-1 mb-2 d-block">Stock</label>
                <select name="stock_status" class="form-select garage-select py-2" onchange="this.form.submit()">
                    <option value="">ALL</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-bsmf-outline py-2 flex-grow-1">FILTER</button>
                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary py-2 px-3 border-opacity-25"><i class="fas fa-undo"></i></a>
            </div>
        </form>
    </div>

    <div class="admin-table-container">
        {{-- Top pagination removed for cleaner look --}}
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
                            <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" alt="" class="product-thumbnail-sm me-3" loading="lazy">
                            <div>
                                <div class="text-white fw-bold">{{ $product->name }}</div>
                                <div class="text-muted small text-uppercase text-xs-tracking">{{ $product->brand->name ?? 'Generic' }} • {{ $product->scale->name ?? '1:64' }}</div>
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
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this collectible from the garage?')" class="d-inline">
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

    <div class="mt-4 d-flex flex-column align-items-center">
        <div class="text-muted smaller fw-normal text-uppercase ls-2 mb-2 opacity-50">
            SHOWING {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} OF {{ $products->total() }} PRODUCTS
        </div>
        <div id="pagination-container">
            {{ $products->links() }}
        </div>
    </div>

@push('modals')
<!-- Universal Product Modal (Floating Card) -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bsmf-modal-content">
            <div class="modal-header bsmf-modal-header">
                <h6 class="modal-title text-white text-uppercase italic fw-black mb-0 modal-header-sm-text" id="productModalLabel">NEW <span>ACQUISITION</span></h6>
                <button type="button" class="btn-close btn-close-white btn-close-sm" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <input type="file" name="main_image" id="main_image_input" class="form-control garage-file-input" accept="image/*" onchange="previewImage(this, 'main_preview_container')">
                            <div id="main_preview_container" class="mt-2 d-flex flex-wrap gap-1"></div>
                        </div>

                        <!-- c. Secondary Images -->
                        <div class="col-md-6 mt-2">
                            <h6 class="section-divider d-flex justify-content-between align-items-center">
                                <span>C. GALLERY (MAX 4)</span>
                                <span id="gallery-counter" class="text-xs text-muted fw-bold">0/4 SLOTS</span>
                            </h6>
                            <input type="file" id="gallery_picker" class="form-control garage-file-input" accept="image/*" multiple onchange="previewImages(this, 'gallery_preview_container')">
                            <input type="file" name="additional_images[]" id="gallery_submission_input" class="d-none" multiple>
                            <div id="gallery_preview_container" class="mt-2 d-flex flex-wrap gap-1"></div>
                        </div>

                        <!-- d. Deletion Queue (Hidden) -->
                        <div id="delete-queue" class="d-none"></div>
                    </div>
                    
                    <div class="mt-3 pt-2 border-top border-secondary border-opacity-25">
                        <button type="submit" id="submitBtn" class="btn btn-warning w-100 py-1 fw-black text-uppercase ls-1 btn-submit-garage">ADD TO GARAGE</button>
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
    galleryFiles = []; // CLEAR THE FILE CACHE

    if (mode === 'add') {
        label.innerHTML = 'NEW <span>ACQUISITION</span>';
        submitBtn.innerHTML = 'ADD TO GARAGE';
        form.action = "{{ route('admin.products.store') }}";
    } else {
        label.innerHTML = 'EDIT <span>COLLECTIBLE</span>';
        submitBtn.innerHTML = 'UPDATE IN GARAGE';
        form.action = `/admin/products/${product.id}`;
        methodField.innerHTML = '';
        
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
        updateGalleryCounter();
    }
}

// CUMULATIVE GALLERY HANDLER
let galleryFiles = []; // Store new file selections here

function updateGalleryCounter() {
    const container = document.getElementById('gallery_preview_container');
    
    // Existing (on server)
    const existing = container.querySelectorAll('.image-preview-wrapper:not(.preview-new-upload):not([style*="display: none"])').length;
    
    // New (in memory)
    const news = galleryFiles.length;
    
    const total = existing + news;
    const counter = document.getElementById('gallery-counter');
    counter.innerText = `${total}/4 SLOTS`;
    
    if (total >= 4) {
        counter.classList.remove('text-muted');
        counter.classList.add('text-danger');
    } else {
        counter.classList.add('text-muted');
        counter.classList.remove('text-danger');
    }

    // SYNC TO HIDDEN SUBMISSION INPUT
    const subInput = document.getElementById('gallery_submission_input');
    const dataTransfer = new DataTransfer();
    galleryFiles.forEach(item => dataTransfer.items.add(item.file));
    subInput.files = dataTransfer.files;
}

function deleteGalleryImage(id) {
    const wrapper = document.getElementById(`gallery_img_${id}`);
    wrapper.style.display = 'none';
    
    // Add to deletion queue
    const queue = document.getElementById('delete-queue');
    queue.innerHTML += `<input type="hidden" name="delete_gallery[]" value="${id}">`;
    
    updateGalleryCounter();
}

function deleteMainImage(productId) {
    const wrapper = document.getElementById('main_img_container');
    wrapper.style.display = 'none';
    
    // Add to deletion queue
    const queue = document.getElementById('delete-queue');
    queue.innerHTML += `<input type="hidden" name="delete_main_image" value="1">`;
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

 // Cumulative Preview for Multiple Images
function previewImages(input, containerId) {
    const container = document.getElementById(containerId);
    
    if (input.files) {
        Array.from(input.files).forEach(file => {
            // Check if we hit the limit
            const currentTotal = container.querySelectorAll('.image-preview-wrapper:not([style*="display: none"])').length;
            if (currentTotal >= 4) return;

            // Add to our memory buffer
            const fileId = Math.random().toString(36).substr(2, 9);
            galleryFiles.push({ id: fileId, file: file });

            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.className = 'image-preview-wrapper preview-new-upload';
                wrapper.id = `new_img_${fileId}`;
                wrapper.innerHTML = `
                    <img src="${e.target.result}" class="image-preview-square border-white">
                    <div class="badge-new-pill">NEW</div>
                    <button type="button" class="delete-image-btn" onclick="removeNewImage('${fileId}')">×</button>
                `;
                container.appendChild(wrapper);
                updateGalleryCounter();
            };
            reader.readAsDataURL(file);
        });

        // Reset the input so the same file can be picked again if deleted
        input.value = '';
    }
}

function removeNewImage(fileId) {
    // Remove from memory
    galleryFiles = galleryFiles.filter(item => item.id !== fileId);
    
    // Remove from UI
    const wrapper = document.getElementById(`new_img_${fileId}`);
    if (wrapper) wrapper.remove();
    
    updateGalleryCounter();
}

// Intercept form submission to inject the accumulated files
document.getElementById('productForm').addEventListener('submit', function(e) {
    // No more manual injection needed here as updateGalleryCounter keeps it in sync
    return true;
});
</script>
@endsection
