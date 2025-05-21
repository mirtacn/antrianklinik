<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AntrianNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $antrian;

    public function __construct($antrian)
    {
        $this->antrian = $antrian;
    }

    public function build()
    {
        return $this->subject('Informasi Antrian Klinik Mabarrot Hasyimiyah')
                    ->view('emails.antrian_notification');
    }
}