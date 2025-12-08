<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewsletterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $unsubscribeUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $content, string $email)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->unsubscribeUrl = route('newsletter.unsubscribe', ['email' => $email]);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.newsletter')
                    ->with([
                        'content' => $this->content,
                        'unsubscribeUrl' => $this->unsubscribeUrl,
                    ]);
    }
}
