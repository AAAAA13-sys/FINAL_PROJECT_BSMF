<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $order_id = $request->query('order_id');
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        $disputes = Dispute::where('user_id', Auth::id())->latest()->get();

        return view('support.index', compact('orders', 'disputes', 'order_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'type' => 'required|in:wrong item,never received,damaged product,other',
            'description' => 'required|string',
        ]);

        Dispute::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'type' => $request->type,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your request has been submitted. Our team will review it shortly.');
    }

    public function messageStore(Request $request, $dispute_id)
    {
        $request->validate(['message' => 'required|string']);

        \App\Models\DisputeMessage::create([
            'dispute_id' => $dispute_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent!');
    }
}
