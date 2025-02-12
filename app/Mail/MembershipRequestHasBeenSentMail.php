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

class MembershipRequestHasBeenSentMail extends Mailable
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
        return $this->subject('Savings Membership Request Sent')
                    ->markdown('emails.membership_request_sent')
                    ->with([
                        'savingName' => $this->saving->title,
                    ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.membership_request_sent',
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
