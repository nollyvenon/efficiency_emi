<?php

namespace Botble\Program\Models;

use Botble\Base\Models\BaseModel;

class Activity extends BaseModel
{
    protected $table = 'activities';
    protected $fillable = [
        'program_id',
        'title',
        'slug',
        'description',
        'content',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
        'location_address',
        'photo',
        'latitude',
        'longitude',
        'status',
        'created_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function registrations()
    {
        return $this->hasMany(ActivityRegistration::class);
    }

    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'activity_registrations');
    }
}
