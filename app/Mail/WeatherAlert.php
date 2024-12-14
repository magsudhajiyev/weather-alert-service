<?php

namespace App\Mail;

use App\DTOs\WeatherAlertData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeatherAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly WeatherAlertData $alertData
    ) {}

    public function build()
    {
        return $this->subject($this->alertData->getMessageSubject())
                    ->markdown('emails.weather-alert', [
                        'alertData' => $this->alertData
                    ]);
    }
}