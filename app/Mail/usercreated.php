<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientEmail;
    public $subject;
    public $jobId;

    /**
     * Create a new message instance.
     *
     * @param string $recipientEmail
     * @param string $subject
     * @param string $jobId
     */
    public function __construct($recipientEmail, $subject, $jobId)
    {
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->jobId = $jobId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
            from: 'maxrai788@gmail.com',
            to: $this->recipientEmail
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.name', // Update with your actual view name
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
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;


    public $subject;
    public $recipientEmail;
    public $jobId;


    /**
     * Create a new message instance.
     *
     * @param string $email
     * @param string $subject
     */
    public function __construct($recipientEmail, $subject,$jobId)
    {
        $this->recipientEmail = $recipientEmail; // Corrected assignment
        $this->subject = $subject;
        $this->jobId = $jobId;
    }
    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->to($this->recipientEmail)
        ->subject($this->subject)
        ->view('mail.name');
        
                    
                    
    }
}