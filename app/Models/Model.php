<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Nitm\Content\Models\BaseModel;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
}