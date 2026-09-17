<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    public function __construct(public Review $review) {}

    public function via(object $notifiable): array
    {
        return ['database'];
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

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}