<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WithdrawalRejectedNotification extends Notification
{
    use Queueable;

    public $withdrawal;

    public function __construct($withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Withdrawal Rejected',
            'message' => 'Your withdrawal of RM '
                . number_format($this->withdrawal->amount, 2)
                . ' has been rejected.',
            'reason' => $this->withdrawal->rejection_reason,
            'withdrawal_id' => $this->withdrawal->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Withdrawal Rejected')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your withdrawal request has been rejected.')
            ->line('Amount: RM ' . number_format($this->withdrawal->amount, 2))
            ->line('**Reason for Rejection:**')
            ->line($this->withdrawal->rejection_reason)
            ->line('Please contact support if you have any questions.')
            ->line('Thank you for using UniNote.');
    }
}
