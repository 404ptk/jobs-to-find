<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobOfferApprovedNotification extends Notification
{
    use Queueable;

    protected $jobOffer;

    /**
     * Create a new notification instance.
     */
    public function __construct($jobOffer)
    {
        $this->jobOffer = $jobOffer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_offer_id' => $this->jobOffer->id,
            'job_offer_title' => $this->jobOffer->title,
            'message' => "Your job offer '{$this->jobOffer->title}' has been approved by the administrator.",
            'type' => 'approval'
        ];
    }
}
