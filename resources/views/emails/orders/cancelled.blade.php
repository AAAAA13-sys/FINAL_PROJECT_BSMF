@component('mail::message')
# Order Cancelled

Hello {{ $order->customer_name }},

We regret to inform you that your order **#{{ $order->order_number }}** has been cancelled.

**Reason for Cancellation:**
{{ $reason }}

If you have any questions, please feel free to contact us.

@component('mail::button', ['url' => config('app.url')])
Return to BSMF GARAGE
@endcomponent

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
