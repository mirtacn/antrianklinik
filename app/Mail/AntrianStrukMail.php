<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Antrian;

class AntrianStrukMail extends Mailable
{
    use Queueable, SerializesModels;

    public $antrian;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Antrian $antrian)
    {
        $this->antrian = $antrian;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Informasi Antrian Klinik Mabarrot Hasyimiyah')
                    ->view('emails.antrian-struk');
    }
}