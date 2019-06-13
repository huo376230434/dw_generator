<?php

namespace App\Notifications\Tenancy;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends \Illuminate\Auth\Notifications\VerifyEmail
{

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'tenancy.verification.verify', Carbon::now()->addMinutes(60), ['id' => $notifiable->getKey()]
        );
    }


}
