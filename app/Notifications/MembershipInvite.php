<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Saving;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MembershipInvite extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $savingGroup;
    public $inviteToken;
    public $user;


    public function __construct(Saving $savingGroup, $inviteToken, User $user)
    {
        $this->savingGroup = $savingGroup;
        $this->inviteToken = $inviteToken;
        $this->user = $user;
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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invitation to Join My Savings Group')
            ->markdown('emails.membership_invite', [
                'user' => $this->user,
                'creator' => $this->savingGroup->creator,
                'saving' => $this->savingGroup,
                'inviteToken' => $this->inviteToken,
                'url' => url('/savings-membership-invite/'.$this->inviteToken.'/'.$this->savingGroup->savings_uid)
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->user,
            'message' => "New savings membership invite",
            'url' => route('saving_invite.show', ['token' => $this->inviteToken, 'savings_uid'=> $this->savingGroup->savings_uid ]),
            'saving_id' => $this->savingGroup->id,
            'invite_token' => $this->inviteToken,
            'invited_by' => $this->savingGroup->creator, // The user who sent the invite
        ];
    }


    public function broadcastWith()
    {
        return [
            'user' => $this->user,
            'message' => "New savings membership invite from ".$this->savingGroup->creator->fullname,
            'url' => route('saving_invite.show', ['token' => $this->inviteToken, 'savings_uid'=> $this->savingGroup->savings_uid ]),
            'saving_id' => $this->savingGroup->id,
            'invite_token' => $this->inviteToken,
            'invited_by' => $this->savingGroup->creator->fullname, // The user who sent the invite
        ];
    }

    // public function broadcastOn()
    // {
    //     return new PrivateChannel('invite.'.$this->user->id);
    // }

    public function toBroadcast(object $notifiable): BroadcastMessage
{
    return new BroadcastMessage([
        'user' => $this->user,
        'message' => "New savings membership invite",
        'url' => route('saving_invite.show', ['token' => $this->inviteToken, 'savings_uid'=> $this->savingGroup->savings_uid ]),
        'saving_id' => $this->savingGroup->id,
        'invite_token' => $this->inviteToken,
        'invited_by' => $this->savingGroup->creator->fullname, 
    ]);
}

    // public function toArray($notifiable)
    // {
    //     return [
    //         'user' => $this->user,
    //         'message' => "New savings membership invite",
    //         'url' => route('saving_invite.show', ['token' => $this->inviteToken, 'savings_uid'=> $this->savingGroup->savings_uid ]),
    //         'saving_id' => $this->savingGroup->id,
    //         'invite_token' => $this->inviteToken,
    //         'invited_by' => $this->savingGroup->creator, // The user who sent the invite
    //     ];
    // }
}
