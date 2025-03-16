<?php
namespace Botble\Applicant\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Botble\Program\Models\Activity;
use Botble\Program\Models\Program;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Applicant extends BaseModel
{
    protected $fillable = ['activity_id', 'program_id', 'user_id', 'highest_degree', 'course_studied', 'passport', 'motivation_letter', 'other_uploads', 'additional_info', 'resume', 'status'];

    /*public function programs()
    {
        return $this->belongsToMany(Program::class)
            ->withPivot(['assigned_by', 'transferred_at', 'deleted_at'])
            ->withTimestamps()
            ->using(ApplicantProgram::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class);
    }*/

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'applicant_program')
            ->withTimestamps(); // Add if you have timestamps
    }

    public function activities(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
