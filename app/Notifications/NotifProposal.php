<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NotifProposal extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $dataNotif;

    public function __construct($dataNotif)
    {
        $this->dataNotif = $dataNotif;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'title'       => $this->dataNotif['title'] ?? 'Notifikasi',
            'message'     => $this->dataNotif['pesan'],
            'proposal_id' => $this->dataNotif['proposal_id'] ?? null,
            'url'         => $this->dataNotif['url'] ?? '#',
            'icon'        => $this->dataNotif['icon'] ?? 'fas fa-bell',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'       => $this->dataNotif['title'] ?? 'Notifikasi Baru',
            'message'     => $this->dataNotif['pesan'],
            'proposal_id' => $this->dataNotif['proposal_id'] ?? null,
            'url'         => $this->dataNotif['url'] ?? '#',
            'icon'        => $this->dataNotif['icon'] ?? 'fas fa-bell',
            'time'        => now()->diffForHumans(),
        ]);
    }
}
