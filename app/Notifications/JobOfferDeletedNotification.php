<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobOfferDeletedNotification extends Notification
{
  use Queueable;

  protected $jobOfferTitle;

  /**
   * Create a new notification instance.
   */
  public function __construct($jobOfferTitle)
  {
    $this->jobOfferTitle = $jobOfferTitle;
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
      'job_offer_id' => null,
      'job_offer_title' => $this->jobOfferTitle,
      'message' => "Your job offer '{$this->jobOfferTitle}' has been deleted by the administrator.",
      'type' => 'deletion'
    ];
  }
}
