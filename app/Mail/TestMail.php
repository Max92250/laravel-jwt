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
    public function __construct($recipientEmail, $jobId)
    {
        $this->recipientEmail = $recipientEmail; // Corrected assignment
    
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
                   
                    ->view('mail.name');
    }
}
