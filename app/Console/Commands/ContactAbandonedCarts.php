<?php

namespace App\Console\Commands;

use App\Helpers\AppHelper;
use App\Models\AbandonedCart;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAbandonedCartByEmail;
use OptimistDigital\NovaSettings\Models\Settings;

class ContactAbandonedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:contact-abandoned-carts
        {--hours= : The number of hours since the last update}
        {--message= : The message to send to the user}
        {--subject= : The subject to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Contact abandoned cart users';

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
        if (!nova_get_setting('abandoned_cart_emails_disabled')) {
            $periods = $this->option('hours') ? collect([
                [
                    'key' => "abandoned_cart_{$this->option('hours')}_hours",
                    'value' => $this->option('hours')
                ]
            ]) : Settings::where('key', 'regexp', 'abandoned_cart_[0-9]+_hours')->whereNotNull('value')->get();
            if ($maxTimes = $periods->count()) {
                $this->info("Need to send {$maxTimes} emails");
                $periods->map(function ($setting) use ($maxTimes) {
                    $fullSettings = AppHelper::getSettingsBasedOnHoursKey($setting['key']);
                    if ($fullSettings->count()) {
                        // dump($fullSettings->toArray());
                        AbandonedCart::where(
                            function ($query) use ($fullSettings) {
                                $query->whereNull('last_emailed_at')->orWhere('last_emailed_at', '<', Carbon::now()->sub('hours', $fullSettings['hours']));
                            }
                        )->distinct('email')
                            ->where('emailed_times', '<', $maxTimes)
                            ->whereNull('signed_up_at')
                            ->get()
                            ->map(
                                function (AbandonedCart $cart) use ($fullSettings) {
                                    if ($cart->email && filter_var($cart->email, FILTER_VALIDATE_EMAIL)) {
                                        $this->info("Sending email to {$cart->email} after {$fullSettings['hours']} hours");
                                        Mail::to($cart->email)->send(new ContactAbandonedCartByEmail($cart, $fullSettings));
                                        $cart->last_emailed_at = Carbon::now();
                                        $cart->emailed_times = $cart->emailed_times + 1;
                                        $cart->save();
                                    } else {
                                        $this->warn("Invalid email {$cart->email}. Deleting!");
                                        $cart->delete();
                                    }
                                }
                            );
                    }
                });
            } else {
                $this->error("No valid settings found");
            }
        } else {
            $this->error("Settings are disabled. Please enable.");
        }
        return 0;
    }
}