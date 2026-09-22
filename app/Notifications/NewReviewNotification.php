<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public Review $review) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        $rating = $this->review->rating;
        $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);

        return [
            'title' => 'تقييم جديد',
            'body'  => $stars . ' على ' . ($this->review->product->name ?? 'منتج'),
            'icon'  => 'heroicon-o-star',
            'color' => $rating >= 4 ? 'success' : ($rating >= 3 ? 'warning' : 'danger'),
            'url'   => '/admin/reviews/' . $this->review->id . '/edit',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id'         => $this->id,
            'title'      => 'تقييم جديد',
            'message'    => 'تقييم جديد على ' . ($this->review->product->name ?? 'منتج'),
            'icon'       => 'star',
            'color'      => $this->review->rating >= 4 ? 'success' : 'warning',
            'url'        => '/admin/reviews/' . $this->review->id . '/edit',
            'created_at' => now()->toISOString(),
        ]);
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.notifications')];
    }

    public function broadcastAs(): string
    {
        return 'new.review';
    }
}