<?php

namespace App\Models;

/**
 * Class Charity
 * @package App\Models
 * @version May 7, 2022, 9:06 pm UTC
 *
 * @property string $name
 * @property string $website
 * @property string $description
 */
class Charity extends Model
{
    public $table = 'charities';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'name',
        'is_active',
        'website',
        'description'
    ];

    protected $with = ['media'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'is_active' => 'boolean',
        'website' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string',
        'website' => 'nullable|string',
        'description' => 'nullable|string'
    ];

    /**
     * The `users()` function returns a collection of users that are associated with the charity
     *
     * @return A collection of users that are associated with the charity.
     */
    public function users()
    {
        return $this->belongsToMany(User\User::class, 'user_charities', 'charity_id', 'user_id');
    }
}