<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'employee_id'; // set primary key
    public $incrementing = true;           // allow auto-increment
    protected $keyType = 'int';            // integer primary key

    protected $fillable = [
        'f_name',
        'l_name',
        'position',
        'e_email',
        'e_num',
        'contact_num', 
        'password',
        'settings',   
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    // Accessor for settings
    public function getSetting($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    // Mutator for settings
    public function setSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->settings = $settings;
        $this->save();
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'employee_id', 'employee_id');
    }
}
