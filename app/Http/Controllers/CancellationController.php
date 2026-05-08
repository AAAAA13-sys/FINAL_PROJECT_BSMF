<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Traits\LogsActions;

final class CancellationController extends Controller
{
    use LogsActions;

    /**
     * Handle user-initiated order cancellation.
     */
    public function cancelByUser(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Only pending acquisitions can be ejected from your garage.');
        }

        $request->validate([
            'reason' => 'required|string',
        ]);

        $reason = $request->reason;

        // "If address (fix address)" logic
        if ($reason === 'Change of Shipping Address' || $reason === 'Incorrect Address Details') {
            return redirect()->route('profile.edit')
                ->with('info', 'To fix your address, please update your collector profile before placing a new order.');
        }

        try {
            DB::statement('CALL sp_CancelOrder(?, ?, ?)', [
                $id,
                Auth::id(),
                $reason
            ]);

            return redirect()->route('orders.index')->with('success', 'Acquisition ejected. Items returned to the main garage.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to eject: ' . $e->getMessage());
        }
    }

    /**
     * Handle admin-initiated order cancellation with email notification.
     */
    public function cancelByAdmin(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'reason' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            DB::statement('CALL sp_CancelOrder(?, ?, ?)', [
                $id,
                Auth::id(),
                $request->reason
            ]);

            // Send Email to Customer
            Mail::to($order->customer_email)->send(new OrderCancelled($order, $request->reason));

            DB::commit();

            return back()->with('success', "Order #{$order->order_number} has been cancelled and notification sent.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Cancellation failed: " . $e->getMessage());
        }
    }
}
