<?php

namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NoteUpdatedNotification extends Notification
{
    use Queueable;

    public $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    public function via($notifiable)
    {
        return ['database']; // in-app notification
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Note Updated',
            'message' => $this->note->provider->name
                . ' updated a note for re-approval.',
            'note_id' => $this->note->id,
            'provider_name' => $this->note->provider->name,
        ];
    }
}
