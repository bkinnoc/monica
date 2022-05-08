<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class UserCharity
 * @package App\Models
 * @version May 8, 2022, 7:59 pm UTC
 *
 * @property integer $user_id
 * @property integer $charity_id
 * @property integer $percent
 */
class UserCharity extends Model
{

    public $table = 'user_charities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'user_id',
        'charity_id',
        'percent'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'charity_id' => 'integer',
        'percent' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|integer',
        'charity_id' => 'required|integer',
        'percent' => 'required|integer'
    ];

    
}
