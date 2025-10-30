<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterUser extends Mailable
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
        return $this->view('emails.welcome')
                    ->subject("Welcome to buanacatering.com!")
                    ->with($this->dataView);
    }
}
