<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MailcowMailbox extends Model
{
    use HasFactory;
    protected $connection = 'mailcow';
    protected $table = 'mailbox';

    /**
     * User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->setConnection(config('database.default'))->belongsTo(User::class, 'username', 'mailbox_key');
    }

    /**
     * Get Id Attribute
     *
     * @return string
     */
    public function getIdAttribute(): string
    {
        return $this->username;
    }
}