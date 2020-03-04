<?php

namespace App\Listeners;

use App\Events\PaymentPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentPlaced as PaymentPlacedEmail;

class OnPaymentPlaced
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PaymentPlaced $event
     * @return void
     */
    public function handle(PaymentPlaced $event)
    {
        Mail::send(new PaymentPlacedEmail);
    }
}
