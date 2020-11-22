<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Purchase;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $purchase;
    public $snowboards;
    public $shipping;

    public function __construct(Purchase $purchase, $snowboards, $shipping)
    {
        $this->purchase = $purchase;
        $this->snowboards = $snowboards;
        $this->shipping = $shipping;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from('boardreserve@gmail.com')->view('emails.orderconfirmation');



        return $email;
    }
}
