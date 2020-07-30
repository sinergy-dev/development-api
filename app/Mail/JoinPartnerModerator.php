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
        return $this->subject($this->customSubject)
        ->view('mail.joinPartner');
    }
}
