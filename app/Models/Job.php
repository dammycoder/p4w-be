<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    //
    protected $table = 'jobs';

    protected $fillable = [
        'about_company',
        'job_title',
        'job_link',
        'job_location',
        'work_type',
        'job_category',
        'title',
        'primary_objectives',
        'job_description',
        'job_responsibilities',
        'reports_to',
        'minimum_qualification',
        'job_code',
    ];
    
}
