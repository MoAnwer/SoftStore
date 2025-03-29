<?php

namespace App\Listeners;

use App\Events\UserRegisterd;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Attribute\AsListener;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

#[AsListener(event: UserRegisterd::class)]
class SendWelcomeEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegisterd $event)
    {
        return response('hello');
    }
}
