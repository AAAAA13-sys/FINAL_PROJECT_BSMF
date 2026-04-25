<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class SupportController extends Controller
{
    /**
     * Display support center.
     */
    public function index(Request $request)
    {
        $order_id = $request->query('order_id');
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        $disputes = Dispute::where('user_id', Auth::id())->latest()->get();

        return view('support.index', compact('orders', 'disputes', 'order_id'));
    }

    /**
     * File a new collector dispute (Deliverable Phase 5).
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'dispute_type' => 'required|in:wrong_item,never_received,damaged_card,not_as_described',
            'description' => 'required|string',
        ]);

        Dispute::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'dispute_type' => $request->dispute_type,
            'description' => $request->description,
            'status' => Dispute::STATUS_PENDING,
        ]);

        return back()->with('success', 'Your dispute has been logged. Our marshals will investigate shortly.');
    }
}
