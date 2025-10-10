<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class GCESendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject, $content, $attachmentList;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $content, $attachmentList)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->attachmentList = $attachmentList;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'app.emails.communication',
            with: [
                'content' => $this->content,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachmentArray = [];
        $i =0 ;
        if(!empty($this->attachmentList)):
            foreach ($this->attachmentList as $attachment) {     
                $disk = (isset($attachment['disk']) && !empty($attachment['disk']) ? $attachment['disk'] : 'local');      
                $attachmentArray[$i++] = Attachment::fromStorageDisk($disk, $attachment["pathinfo"])
                ->as($attachment["nameinfo"])
                ->withMime($attachment["mimeinfo"]);
            }
        endif;
        
        return $attachmentArray;
    }
}
