<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WithdrawalApprovedNotification extends Notification
{
    use Queueable;

    public $withdrawal;

    public function __construct($withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // remove 'mail' if you want in-app only
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Withdrawal Approved',
            'message' => 'Your withdrawal of RM '
                . number_format($this->withdrawal->amount, 2)
                . ' has been approved.',
            'proof_url' => asset('storage/' . $this->withdrawal->payment_proof),
            'withdrawal_id' => $this->withdrawal->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Withdrawal Approved')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your withdrawal request has been approved.')
            ->line('Amount: RM ' . number_format($this->withdrawal->amount, 2))
            ->action(
                'View Payment Proof',
                asset('storage/' . $this->withdrawal->payment_proof)
            )
            ->line('Thank you for using UniNote.');
    }
}

