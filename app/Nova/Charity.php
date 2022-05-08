<?php

namespace App\Nova;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Filters\CharityStatus;
use App\Nova\Actions\ActivateCharity;
use Laravel\Nova\Fields\BelongsToMany;
use App\Nova\Actions\DeactivateCharity;
use Laravel\Nova\Http\Requests\NovaRequest;

class Charity extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Charity::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        // adds a `tags_count` column to the query result based on
        // number of tags associated with this product
        return $query->withCount('users');
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
            ID::make(__('ID'), 'id')->sortable(),
            new Panel("Media", $this->mediaField()),
            Text::make(__('Name'), 'name')->sortable(),
            Text::make(__('Website'), 'website')->nullable(),
            Boolean::make(__('Is Active'), 'is_active')->sortable(),
            Number::make(__('User Count'), 'users_count')->sortable()->exceptOnForms(),
            Trix::make(__('Description'), 'description')->nullable(),
            BelongsToMany::make(__('Users'), 'users', User::class),
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
        return [
            CharityStatus::make(),
        ];
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
            ActivateCharity::make()->showOnTableRow(),
            DeactivateCharity::make()->showOnTableRow(),
        ];
    }
}