<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class BadWord
 * @package App\Models
 * @version May 13, 2022, 12:27 am UTC
 *
 * @property string $word
 * @property string $language
 */
class BadWord extends Model
{

    public $table = 'bad_words';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'word',
        'language'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'word' => 'string',
        'language' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'word' => 'required|string',
        'language' => 'required|string'
    ];

    protected $attributes = [
        'language' => 'en'
    ];
}