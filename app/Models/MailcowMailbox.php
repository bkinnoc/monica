<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailcowMailbox extends Model
{
    use HasFactory;
    protected $connection = 'mailcow';
    protected $table = 'mailbox';
}