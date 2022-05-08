<?php

namespace App\Nova\Filters;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class CharityStatus extends BooleanFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        if (Arr::get($value, 'active') === true) {
            $query->where('is_active', true);
        } elseif (Arr::get($value, 'inactive') === true) {
            $query->where('is_active', false);
        }
        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Active' => 'active',
            'Inactive' => 'inactive',
        ];
    }
}