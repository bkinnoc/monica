<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Carbon\Traits\Timestamp;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use App\Nova\Actions\EmailAbandonedCart;
use Laravel\Nova\Http\Requests\NovaRequest;
use Vyuldashev\NovaPermission\RoleBooleanGroup;
use Vyuldashev\NovaPermission\PermissionBooleanGroup;

class AbandonedCart extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AbandonedCart::class;

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
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'last_name', 'mailbox_key', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('User', 'user'),

            Text::make('First Name')
                ->sortable()
                ->readonly(),

            Text::make('Last Name')
                ->sortable()
                ->readonly(),

            Text::make('Email')
                ->sortable()
                ->readonly(),

            Text::make('Mailbox Key')
                ->sortable()
                ->readonly(),
            DateTime::make('Signed Up At'),
            DateTime::make('Created At')
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
        return [
            new EmailAbandonedCart
        ];
    }
}