<?php

namespace App\Listeners;

use App\Events\UserRegister;
use App\Services\Auth\RegisterService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailCode
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected readonly RegisterService $registerService,
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegister $event): void
    {
        $this->registerService->sendCode($event->user->id);
    }
}
