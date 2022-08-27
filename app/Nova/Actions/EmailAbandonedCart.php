<?php

namespace App\Nova\Actions;

use App\Helpers\AppHelper;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Laravel\Telescope\Telescope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ContactAbandonedCartByEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use OptimistDigital\NovaSettings\Models\Settings;

class EmailAbandonedCart extends Action
{
    use InteractsWithQueue, Queueable;

    public $showOnTableRow = true;

    public $name = "Remind User";

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if (!$fields['select_email_to_send']) {
            return Action::danger("No email selected!");
        }
        $models = $models->filter(function ($model) {
            return filter_var($model->email, FILTER_VALIDATE_EMAIL) && !$model->signed_up_at;
        });
        if ($models->count()) {
            $models->map(
                function ($model) use ($fields) {
                    $settings = AppHelper::getSettingsBasedOnHoursKey($fields['select_email_to_send']);
                    Mail::to($model->email)->send(new ContactAbandonedCartByEmail($model, $settings));
                }
            );
            return Action::message("Sent email to {$models->count()} " . ($models->count() == 1 ? "person" : "people"));
        }
        return Action::danger("No valid people found in the list!");
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('Select Email to Send')
                ->required()
                ->options(
                    Settings::where('key', 'regexp', 'abandoned_cart_[0-9]+_hours')->whereNotNull('value')->get()->map(
                        function ($setting) {
                            $key = str_replace(['abandoned_cart_', '_hours'], '', $setting['key']);
                            return [
                                'label' => "Email {$key} sent after {$setting['value']} hours",
                                'value' => $setting['key'],
                                'key' => $setting['key']
                            ];
                        }
                    )->keyBy('key')
                )
        ];
    }
}