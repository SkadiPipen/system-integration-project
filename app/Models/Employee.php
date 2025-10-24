<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'employee_id'; // set primary key
    public $incrementing = true;           // allow auto-increment
    protected $keyType = 'int';            // integer primary key

    protected $fillable = [
        'f_name',
        'l_name',
        'position',
        'e_email',
        'e_num',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
