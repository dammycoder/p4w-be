<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    //
    protected $table = 'subscribers';

    protected $fillable = [
        'email',
        'unsubscribed',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'unsubscribed' => 'boolean',
    ];
}
