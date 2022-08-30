<?php

namespace App\Nova\Metrics;

use App\Models\UserCharity;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Http\Requests\NovaRequest;

class UsersPerCharity extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count(
            $request,
            UserCharity::with('charity')->join('charities', 'charities.id', 'user_charities.charity_id')
                ->select(
                    [
                        'id', 'charity_id', 'charites.name as charity_name', 'user_id'
                    ]
                ),
            'charities.name',
            'charity_id'
        );
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'users-per-charity';
    }
}