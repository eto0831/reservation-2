<?php

namespace App\Jobs;

use App\Mail\ReservationReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendReminderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('SendReminderEmailJob: Sending email for reservation ID ' . $this->reservation->id);

            Mail::to($this->reservation->user->email)
                ->send(new ReservationReminderMail($this->reservation));

            Log::info('SendReminderEmailJob: Email sent successfully for reservation ID ' . $this->reservation->id);
        } catch (\Exception $e) {
            Log::error('SendReminderEmailJob: Failed to send email for reservation ID ' . $this->reservation->id . '. Error: ' . $e->getMessage());
        }
    }
}
