<x-mail::message>
# Your Models are on the Move! 🏎️💨

Hi {{ $order->customer_name }},

Exciting news! Your acquisition **#{{ $order->order_number }}** from BSMF GARAGE has been dispatched and is currently in transit to your collection.

**Tracking Details:**
- **Courier:** {{ $order->courier_name ?? 'N/A' }}
- **Tracking Number:** `{{ $order->tracking_number ?? 'N/A' }}`

<x-mail::button :url="$order->tracking_link">
Track Acquisition
</x-mail::button>

Please ensure someone is available at your shipping address to receive the delivery.

Thank you for being part of the BSMF elite collection community!

Regards,  
The BSMF Team
</x-mail::message>
