<?php

namespace App\Nova;

use App\Helpers\AppHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Helpers\InstanceHelper;
use Laravel\Nova\Fields\Number;
use Laravel\Cashier\Subscription;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Vyuldashev\NovaPermission\RoleBooleanGroup;
use Vyuldashev\NovaPermission\PermissionBooleanGroup;

class MailcowMailbox extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User\User::class;

    /**
     * @inheritDoc
     *
     * @var string
     */
    public static $group = 'Users';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'username';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'first_name', 'last_name', 'mailbox_key'
    ];

    /**
     * Title
     *
     * @return void
     */
    public static function label()
    {
        return 'Mailboxes';
    }

    /**
     * Index Query
     *
     * @param  mixed $request
     * @param  mixed $query
     * @return void
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with('account',  'mailbox')->whereNotNull('mailbox_key');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Name', 'name')
                ->sortable()
                ->readonly(),
            Text::make('Mailbox', 'mailbox_key')
                ->sortable()
                ->readonly(),
            Text::make('Subscription')->displayUsing(function () {
                $subscription = $this->account->getSubscribedPlan();
                $plan = $subscription instanceof Subscription ? InstanceHelper::getPlanInformationFromSubscription($subscription) : [];
                return implode(
                    ' @ ',
                    [
                        Str::title(Arr::get($plan, 'name') ?: Arr::get($plan, 'type') ?: 'Unnamed Plan'),
                        Arr::get($plan, 'friendlyPrice') ?: '$0.00',
                    ]
                );
            }),
            Text::make('Total Storage')->displayUsing(function () {
                return AppHelper::bytesToHumanReadable(optional($this->mailbox)->quota);
            }),
            Text::make('Available Storage')->displayUsing(function () {
                return AppHelper::bytesToHumanReadable(optional($this->mailbox)->quota);
            })
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}