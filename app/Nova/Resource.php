<?php

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;

abstract class Resource extends NovaResource
{
    /**
     * Disable import by default
     *
     * @var boolean
     */
    public static $canImportResource = false;

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }
    /**
     * Media Fields
     *
     * @param mixed $title
     * @param mixed $property
     *
     * @return array
     */
    protected function mediaField($title = 'Media', $property = 'media'): array
    {
        $field = Media::make($title, $property)
            ->enableExistingMedia()
            ->conversionOnDetailView('medium') // conversion used on the model's view
            ->conversionOnIndexView('thumb') // conversion used to display the image on the model's index page
            ->conversionOnForm('medium') // conversion used to display the image on the model's form
            ->fullSize(); // full size column;
        if (config('filesystems.default') == 's3') {
            // $field->temporary(now()->addMinutes(15));
        }
        return  [
            $field
        ];
    }
    /**
     * Media Fields
     *
     * @param mixed $title
     * @param mixed $property
     *
     * @return array
     */
    protected function mediaFields($title = 'Media', $property = 'media'): array
    {
        $field = Media::make($title, $property)
            ->readOnly()
            ->onlyOnDetail()
            ->enableExistingMedia()
            ->conversionOnDetailView('thumb') // conversion used on the model's view
            ->conversionOnIndexView('thumb') // conversion used to display the image on the model's index page
            ->conversionOnForm('thumb') // conversion used to display the image on the model's form
            ->fullSize() // full size column
            ->withResponsiveImages();
        if (config('filesystems.default') == 's3') {
            // $field->temporary(now()->addMinutes(15));
        }
        return  [
            $field
        ];
    }
}