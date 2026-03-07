<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationAcceptedNotification extends Notification
{
  use Queueable;

  protected $application;
  protected $jobTitle;

  /**
   * Create a new notification instance.
   */
  public function __construct($application, $jobTitle)
  {
    $this->application = $application;
    $this->jobTitle = $jobTitle;
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
      'application_id' => $this->application->id,
      'job_offer_id' => $this->application->job_offer_id,
      'job_title' => $this->jobTitle,
      'message' => "Your application for the position '{$this->jobTitle}' has been accepted!",
      'type' => 'application_accepted'
    ];
  }
}
