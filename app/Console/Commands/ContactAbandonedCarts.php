<?php

namespace App\Console\Commands;

use App\Models\AbandonedCart;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAbandonedCartByEmail;

class ContactAbandonedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        AbandonedCart::where(
            function ($query) {
                $query->whereNull('last_emailed_at')->orWhere('last_emailed_at', '<', Carbon::now()->sub('hours', 24));
            }
        )->where('emailed_times', '<', 3)
            ->get()
            ->map(
                function (AbandonedCart $cart) {
                    if ($cart->email) {
                        Mail::to($cart->email)->send(new ContactAbandonedCartByEmail($cart));
                        $cart->last_emailed_at = Carbon::now();
                        $cart->emailed_times = $cart->emailed_times + 1;
                        $cart->save();
                    }
                }
            );
        return 0;
    }
}