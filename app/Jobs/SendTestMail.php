<?php

namespace App\Jobs;

use App\Mail\TestMail;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTestMail implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipientEmail;
    protected $jobId;

    public function __construct($recipientEmail, $jobId)
    {
        $this->recipientEmail = $recipientEmail;
        $this->jobId = $jobId;
        
    }

    public function handle()
    {
        Mail::to($this->recipientEmail)->send(new TestMail($this->recipientEmail, 'Generic Email', $this->jobId));
    }

   
    
  
}
