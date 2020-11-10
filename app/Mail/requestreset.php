<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class requestreset extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $reset;

    public function __construct($reset)
    {
        $this->reset = $reset;    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

       return $this->from($address = 'no-reply@interrupt21.id', $name = 'Interrupt 21 Support')
                   ->subject('Request Reset Token')
                   ->view('email.requestreset');
    }
}
