<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Invite;
use App\Models\Saving;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MembershipInviteAccepted extends Notification
{
    use Queueable;

   public $inviteToken;
   public $user;
   public $savingGroup;
   public $creator_obj;

    public function __construct($inviteToken, User $user, Saving $savingGroup, $creator_obj)
    {
        $this->inviteToken = $inviteToken;
        $this->user = $user;
        $this->savingGroup = $savingGroup;
        $this->creator_obj = $creator_obj;
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
                ->subject('Savings Membership Invitation Has Been Accepted.')
                ->markdown('emails.membership_invite_accepted', [
                    'user' => $this->user,
                    'creator' => $this->creator_obj,
                    'saving' => $this->savingGroup,
                    'invite_token' => $this->inviteToken,
                    'url' => url('/savings-invite-accepted/'.$this->inviteToken.'/'.$this->savingGroup->savings_uid)
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
            'message' => "Membership Invite Accepted",
            'url' => route('invite_accepted.show', ['token' => $this->inviteToken, 'savings_uid'=> $this->savingGroup->savings_uid ]),
            'saving_id' => $this->savingGroup->id,
            'invite_token' => $this->inviteToken,
            'user' => $this->user->fullname, 
        ];
    }

    public function toBroadcast($notifiable){
        return new BroadcastMessage([
            'message' => "{$this->user->fullname} has accepted your invitation",
            'saving_id' => $this->savingGroup->id, // Include the saving ID.  VERY IMPORTANT!
            'user' => $this->user->fullname,
            'url' => route('invite_accepted.show', ['token' => $this->inviteToken, 'savings_uid'=> $this->savingGroup->savings_uid ]),
            'invite_token' => $this->inviteToken,
        ]);
    }

    public function broadcastOn()
    {
        // Broadcast to the saving-group.{groupId} channel
        return new PrivateChannel('savings.' . $this->savingGroup->savings_uid . '.creator');
    }

    //  public function broadcastType()
    // {
    //     return 'InviteAccepted'; // Customize this event name
    // }
}
