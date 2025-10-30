<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExpireUser extends Mailable
{
    use Queueable, SerializesModels;
    public $dataView;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataView)
    {
        //
        $this->dataView = $dataView;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.goodbye')
                    ->from('support@mg.user.com', "user")
                    ->replyTo('support@mg.user.com', "user")
                    ->subject("Thank you and goodbye from user.com!")
                    ->with($this->dataView);
    }
}
