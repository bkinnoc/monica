<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class BadWord extends Resource
{
    public static $canImportResource = true;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\BadWord::class;

    /**
     * @inheritDoc
     *
     * @var string
     */
    public static $group = 'Settings';

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
            Text::make(__('Word'))->sortable()->help("You can use the follwing patterns: <ul><li>" . implode("</li><li>", [
                "<b>^WORD</b> to match the beginning of a word: WORD@aol.com Match: No Match: aolWORD@aol.com",
                "<b>WORD$</b> to match the end of a word Match: admin@WORD Match: No Match: aolWORD@aol.com",
                "<b>WORD</b> to part of a word Match: any email or sentence containing WORD",
                "<b>WORD*</b> to match a word followed by any other characters Match: admin@aol.com Match: Match: aoladmin@aol.com",
            ]) . "</li></ul>")
                ->rules(['required', 'string', 'regex:/[a-z0-9\$\@\-\_\$\^]*/']),
            Text::make(__('Language'))->sortable(),
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