<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'success',
        'reason',
        'city',
        'country',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'success' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'created_at' => 'datetime'
    ];

    /**
     * Get the user that owns the login attempt
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}



