<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Payment extends Model
{
    //
    protected $table = 'payments';

 
    protected $fillable = [
        'reference', 'amount', 'firstname', 'lastname', 'email', 'status'
    ];

}
