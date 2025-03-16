<?php

namespace Botble\Event\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Event extends BaseModel
{
    protected $table = 'events';

    protected $fillable = ['name', 'description', 'content', 'start_time', 'end_time', 'photo', 'location_address', 'capacity', 'latitude', 'longitude', 'status'];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];
}
