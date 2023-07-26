<?php

namespace App\Listeners;

use App\Events\PurchaseCompleted;
use App\Jobs\SendPurchaseConfirmationEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPurchaseConfirmationEmail
{
    /**
     * Handle the event.
     */
    public function handle(PurchaseCompleted $event): void
    {
        SendPurchaseConfirmationEmailJob::dispatch($event->order); 
    }
}
