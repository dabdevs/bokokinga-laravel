<?php

namespace App\Jobs;

use App\Mail\PurchaseConfirmationMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPurchaseConfirmationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order; 
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    { 
        Mail::to($this->order->customer->email)->send(new PurchaseConfirmationMail($this->order));
        dd('sending mail');
    }
}
