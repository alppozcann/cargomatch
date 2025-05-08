<?php

namespace App\Notifications;

use App\Models\GemiRoute;
use App\Models\Yuk;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CargoMatchRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $gemiRoute;
    public $yuk;
    public $matchScore;

    public function __construct(GemiRoute $gemiRoute, Yuk $yuk, float $matchScore)
    {
        $this->gemiRoute = $gemiRoute;
        $this->yuk = $yuk;
        $this->matchScore = $matchScore;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Yeni Yük Eşleşme Talebi')
            ->greeting('Merhaba ' . $notifiable->name)
            ->line('Gemi rotanız için yeni bir yük eşleşme talebi var.')
            ->line('Eşleşme Skoru: ' . number_format($this->matchScore * 100, 1) . '%')
            ->action('Detayları Gör', url('/matches/' . $this->gemiRoute->id))
            ->line('Lütfen eşleşme talebini inceleyin.');
    }

    public function toArray($notifiable)
    {
        return [
            'gemi_route_id' => $this->gemiRoute->id,
            'yuk_id' => $this->yuk->id,
            'match_score' => $this->matchScore,
            'route_title' => $this->gemiRoute->title,
            'start_port' => optional($this->gemiRoute->startPort)->name,
            'end_port' => optional($this->gemiRoute->endPort)->name,
            'departure_date' => optional($this->gemiRoute->departure_date)->format('d.m.Y'),
            'arrival_date' => optional($this->gemiRoute->arrival_date)->format('d.m.Y'),
            'capacity' => $this->gemiRoute->available_capacity,
            'message' => 'Yeni yük eşleşme talebi (' . number_format($this->matchScore * 100, 1) . '%)',
            'type' => 'cargo_match_request'
        ];
    }
    public function toDatabase($notifiable)
{
    return [
        'gemi_route_id' => $this->gemiRoute->id,
        'yuk_id' => $this->yuk->id,
        'match_score' => $this->matchScore,
        'message' => 'Yeni yük eşleşme talebi (' . number_format($this->matchScore * 100, 1) . '%)',
        'type' => 'cargo_match_request'
    ];
}
}
