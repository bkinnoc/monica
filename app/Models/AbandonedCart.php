<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AbandonedCart
 * @package App\Models
 * @version August 6, 2022, 9:48 pm UTC
 *
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string|\Carbon\Carbon $last_emailed_at
 * @property string|\Carbon\Carbon $signed_up_at
 * @property integer $emailed_times
 * @property integer $user_id
 */
class AbandonedCart extends Model
{

    public $table = 'abandoned_cart';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'email',
        'first_name',
        'last_name',
        'last_emailed_at',
        'signed_up_at',
        'emailed_times',
        'mailbox_key',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'mailbox_key' => 'string',
        'last_emailed_at' => 'datetime',
        'signed_up_at' => 'datetime',
        'emailed_times' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'email' => 'required|string',
        'mailbox_key' => 'required|string',
        'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'last_emailed_at' => 'nullable',
        'signed_up_at' => 'nullable',
        'emailed_times' => 'required|integer',
        'user_id' => 'nullable|integer'
    ];

    /**
     * User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User\User::class);
    }
}