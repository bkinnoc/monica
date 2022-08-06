<?php

namespace App\Mail;

use App\Models\AbandonedCart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAbandonedCartByEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The abandoned cart
     *
     * @var AbandonedCart
     */
    public $cart;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AbandonedCart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.contact-abandoned-cart-by-email');
    }
}