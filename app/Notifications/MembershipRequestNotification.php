<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Saving;
use Illuminate\Bus\Queueable;
use App\Mail\MembershipRequestMail;
use App\Models\MembershipRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MembershipRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $saving;
    public $memReq;

    public function __construct(User $user, Saving $saving, MembershipRequest $memReq)
    {
        $this->user = $user;
        $this->saving = $saving;
        $this->memReq = $memReq;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'message' => "New membership request from {$this->user->first_name}",
            'url' => route('show.membership_req', ['id' => $this->memReq->id]), // Dynamic link
            'req_id' => $this->memReq->id,
            'user_id' => $this->user->id,
            'saving_id' => $this->saving->id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
{
    return (new MembershipRequestMail($this->user, $this->saving))->to($notifiable->email);
}

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->user->fullname} has requested to join your savings group: {$this->saving->title}.",
            'user_id' => $this->user->id,
            'saving_id' => $this->saving->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "{$this->user->name} has requested to join your savings group: {$this->saving->title}.",
            'user_id' => $this->user->id,
            'saving_id' => $this->saving->id,
        ]);
    }
}
