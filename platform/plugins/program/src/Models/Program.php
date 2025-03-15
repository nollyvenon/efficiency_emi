<?php

namespace Botble\Program\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;

class Program extends BaseModel
{
    use EnumCastable;

    protected $table = 'programs';
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'photo',
        'status'
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    
}
