<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectContent;
    public $messageContent;

    public function __construct($subjectContent, $messageContent)
    {
        $this->subjectContent = $subjectContent;
        $this->messageContent = $messageContent;
    }

    public function build()
    {
        return $this->subject($this->subjectContent)
                    ->view('emails.notification')
                    ->with([
                        'subjectContent' => $this->subjectContent,
                        'messageContent' => $this->messageContent,
                    ]);
    }
}
