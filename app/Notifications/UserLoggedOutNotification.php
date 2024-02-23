<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserLoggedOutNotification extends Notification
{
    use Queueable;

    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('You have been successfully login out')
        ->greeting('Hello!')
        ->line('You have been successfully logged out from our application.')
        ->line('We hope you had a great experience with us.')
        ->line('If you have any questions or need assistance, feel free to contact our support team.')
        ->action('Login Again', url('/api/users/login'))
        ->line('If you did not perform this action, please contact our support team immediately.')
        ->attach(public_path('images/1706760814_pop.jpg'), [
            'as' => '1706760814_pop.jpg',
            'mime' => 'image/png',
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have been logged out successfully.',
            'action' => url('/dashboard'),
        ];
    }

    // Add this method for supported channels
    public function via($notifiable)
    {
        return ['mail', 'database'];  // Add other channels if needed
    }
}
