<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Saving;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class MembershipRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $saving;

    public function __construct(User $user, Saving $saving)
    {
        $this->user = $user;
        $this->saving = $saving;
    }

    
    public function build()
    {
        return $this->subject('New Savings Group Request')
                    ->markdown('emails.membership_request')
                    ->with([
                        'userName' => $this->user->fullname,
                        'savingName' => $this->saving->title,
                        'savingUrl' => url('/saving/' . $this->saving->savings_uid),
                    ]);
    }

    
    
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.membership_request',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
