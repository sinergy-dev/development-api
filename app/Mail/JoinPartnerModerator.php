<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinPartnerModerator extends Mailable
{
    use Queueable, SerializesModels;
    public $randomString,$partner,$activity,$customSubject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($randomString,$partner,$activity,$customSubject)
    {
        //
        $this->randomString = $randomString;
        $this->partner = $partner;
        $this->activity = $activity;
        $this->customSubject = $customSubject;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->partner->status == "On Progress") {
            return $this->subject($this->customSubject)
            ->view('mail.fillProgress');
        }else if($this->partner->status == "OK Advance"){
            return $this->subject($this->customSubject)
            ->view('mail.uploadProgress');
        }else if($this->partner->status == "OK Interview"){
            return $this->subject($this->customSubject)
            ->view('mail.resultsProgress');
        }else if($this->partner->status == "OK Agreement"){
            return $this->subject($this->customSubject)
            ->view('mail.joinPartner');
        }else if($this->partner->status == "OK Partner"){
            return $this->subject($this->customSubject)
            ->view('mail.partnerProgress');
        }else{
            return $this->subject($this->customSubject)
            ->view('mail.rejectedProgress');
        }
        
    }
}
