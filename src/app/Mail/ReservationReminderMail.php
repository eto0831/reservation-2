<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReservationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        Log::info('ReservationReminderMail: Building email for reservation ID ' . $this->reservation->id);

        return $this
            ->subject('【リマインド】予約日のお知らせ')
            ->view('emails.reservation_reminder')
            ->with(['reservation' => $this->reservation]);
    }
}
