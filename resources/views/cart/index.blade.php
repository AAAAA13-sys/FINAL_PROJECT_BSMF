@extends('layouts.app')

@section('title', 'Your Pit Stop - Shopping Cart')

@section('content')
<section class="section-padding">
    <div class="section-header">
        <h2 class="section-title">Your <span>Pit Stop</span></h2>
    </div>

    @if($cartItems->count() > 0)
        <div class="glass" style="padding: 2rem; margin-top: 2rem;">
            <table style="width: 100%; border-collapse: collapse; color: white;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--secondary); text-align: left;">
                        <th style="padding: 1rem;">PRODUCT</th>
                        <th style="padding: 1rem;">PRICE</th>
                        <th style="padding: 1rem;">QUANTITY</th>
                        <th style="padding: 1rem;">SUBTOTAL</th>
                        <th style="padding: 1rem;">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                        @php $subtotal = $item->product->price * $item->quantity; $total += $subtotal; @endphp
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                                <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" style="width: 80px; height: 50px; object-fit: contain; background: white; border-radius: 4px;">
                                <span style="font-weight: 700;">{{ $item->product->name }}</span>
                            </td>
                            <td style="padding: 1rem;">${{ number_format($item->product->price, 2) }}</td>
                            <td style="padding: 1rem;">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" style="width: 60px; padding: 0.3rem; background: rgba(0,0,0,0.3); border: 1px solid var(--secondary); color: white; border-radius: 4px;">
                                    <button type="submit" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.7rem;">Update</button>
                                </form>
                            </td>
                            <td style="padding: 1rem; color: var(--secondary); font-weight: 800;">${{ number_format($subtotal, 2) }}</td>
                            <td style="padding: 1rem;">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: var(--danger); background: none; border: none; cursor: pointer; font-weight: 700;">REMOVE</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 3rem; display: flex; justify-content: flex-end; align-items: center; gap: 4rem;">
                <div style="text-align: right;">
                    <p style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Estimated Total</p>
                    <p style="font-size: 2.5rem; color: var(--secondary); font-weight: 900; text-shadow: 2px 2px 0px var(--primary);">${{ number_format($total, 2) }}</p>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-primary" style="padding: 1.5rem 4rem; font-size: 1.2rem;">CHECKOUT NOW</a>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 5rem 0;">
            <p style="font-size: 1.5rem; color: var(--text-muted); margin-bottom: 2rem;">Your cart is currently empty.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">GO TO SHOP</a>
        </div>
    @endif
</section>
@endsection
