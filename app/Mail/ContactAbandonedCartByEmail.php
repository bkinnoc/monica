<?php

namespace App\Mail;

use App\Models\AbandonedCart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
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
     * The setting containing the email information
     *
     * @var Collection
     */
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AbandonedCart $cart, Collection $settings)
    {
        $this->settings = $settings;
        $this->cart = $cart;

        $this->afterCommit();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject($this->replaceVariables($this->settings['subject']));
        return $this->markdown('mail.contact-abandoned-cart-by-email', [
            'body' => $this->replaceVariables($this->settings['email']),
            'url' => url(config('app.url')) . '/register?token=' . Crypt::encryptString($this->cart->email)
        ]);
    }

    /**
     * Replace Variables
     *
     * @param  mixed $text
     * @return void
     */
    protected function replaceVariables(string $text)
    {
        return str_replace(
            [
                '{{name}}',
                '{{email}}',
                '{{firstName}}',
                '{{lastName}}',
            ],
            [
                $this->cart->first_name . ' ' . $this->cart->last_name,
                $this->cart->email,
                $this->cart->first_name,
                $this->cart->last_name
            ],
            $text
        );
    }
}