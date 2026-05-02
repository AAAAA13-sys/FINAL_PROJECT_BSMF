@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="card-title-premium fs-2">EDIT <span>PRODUCT</span></h1>
        <a href="{{ route('admin.products') }}" class="btn-bsmf-outline">BACK TO GARAGE</a>
    </div>

    <div class="glass-panel overflow-hidden mx-auto" style="max-width: 900px; border: 2px solid var(--secondary-blue);">
        <div class="p-4 px-5 border-bottom border-secondary border-opacity-25 bg-darker">
            <h2 class="h5 text-white text-uppercase italic mb-0 fw-black">MODIFY <span>GRAIL DETAILS</span></h2>
        </div>
        <div class="p-5 bg-darker">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <label class="input-label-sm">Product Display Name</label>
                            <input type="text" name="name" class="form-control garage-input p-3" value="{{ $product->name }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="input-label-sm">Casting Name</label>
                            <input type="text" name="casting_name" class="form-control garage-input p-3" value="{{ $product->casting_name }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="input-label-sm">Brand</label>
                            <select name="brand_id" class="form-select garage-select p-3" required>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" {{ $product->brand_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="input-label-sm">Scale</label>
                            <select name="scale_id" class="form-select garage-select p-3" required>
                                @foreach($scales as $s)
                                    <option value="{{ $s->id }}" {{ $product->scale_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="input-label-sm">Series (Optional)</label>
                            <select name="series_id" class="form-select garage-select p-3">
                                <option value="">None</option>
                                @foreach($series as $ser)
                                    <option value="{{ $ser->id }}" {{ $product->series_id == $ser->id ? 'selected' : '' }}>{{ $ser->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="input-label-sm">Price (₱)</label>
                            <input type="number" step="0.01" min="0" name="price" class="form-control garage-input p-3" value="{{ $product->price }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="input-label-sm">Stock Quantity</label>
                            <input type="number" min="0" name="stock_quantity" class="form-control garage-input p-3" value="{{ $product->stock_quantity }}" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <label class="input-label-sm">Description</label>
                            <textarea name="description" class="form-control garage-textarea p-3" rows="5">{{ $product->description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="btn-bsmf-primary w-100 py-3 ls-2">COMMIT CHANGES TO GARAGE</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
