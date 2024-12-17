<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    //
    protected $table = 'volunteers';

    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'gender',
        'address',
        'email',
        'city',
        'state',
        'how_did_you_hear',
        'aware_of_payment_policy',
        'approved',
        'instagram_handle',
        'backup_phone_number',
        'terms_and_conditions',
        'available_for_outreach',
        'skills',
        'availability',
        'motivation',
        'hopes',
    ];

    protected $casts = [
        'age' => 'integer',
        'aware_of_payment_policy' => 'boolean',
        'terms_and_conditions' => 'boolean',
        'available_for_outreach' => 'boolean',
    ];
}
