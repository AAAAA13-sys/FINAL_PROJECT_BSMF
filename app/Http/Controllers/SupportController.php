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
        if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
            $disputes = Dispute::where('user_id', Auth::id())->with('order')->orderBy('created_at', 'desc')->get();
            return \App\Http\Resources\DisputeResource::collection($disputes);
        }

        $order_id = $request->query('order_id');
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        $disputes = Dispute::where('user_id', Auth::id())->latest()->get();

        return view('support.index', compact('orders', 'disputes', 'order_id'));
    }

    /**
     * Display specific dispute.
     */
    public function show(Request $request, $id)
    {
        $dispute = Dispute::where('user_id', Auth::id())
            ->with(['order', 'messages.user'])
            ->findOrFail($id);

        if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
            return new \App\Http\Resources\DisputeResource($dispute);
        }

        return view('support.show', compact('dispute'));
    }

    /**
     * File a new collector dispute.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'dispute_type' => 'required|string',
            'description' => 'required|string',
        ]);

        // Ensure order belongs to user
        $order = Order::where('user_id', Auth::id())->where('id', $request->order_id)->firstOrFail();

        $dispute = Dispute::create([
            'dispute_number' => 'DSP-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'subject' => $request->subject ?? $request->dispute_type,
            'description' => $request->description,
            'status' => 'open',
        ]);

        if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
            return new \App\Http\Resources\DisputeResource($dispute);
        }

        return back()->with('success', 'Your dispute has been logged. Our marshals will investigate shortly.');
    }

    /**
     * Add message to dispute.
     */
    public function messageStore(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $dispute = Dispute::where('user_id', Auth::id())->findOrFail($id);

        $message = \App\Models\DisputeMessage::create([
            'dispute_id' => $dispute->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $dispute->touch();

        if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
            return new \App\Http\Resources\DisputeMessageResource($message);
        }

        return back()->with('success', 'Reply dispatched.');
    }
}
