<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmationOtp extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Final Lap: Confirm Your Acquisition - BSMF Garage')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You are one step away from finalizing your latest acquisition at BSMF Garage.')
            ->line('Please enter the following 6-digit confirmation code in the terminal to lock in your order:')
            ->line('**' . $this->otp . '**')
            ->line('This code is valid for 10 minutes. If you did not initiate this order, please ignore this email.')
            ->line('Thank you for being part of the elite collector community.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
        ];
    }
}
