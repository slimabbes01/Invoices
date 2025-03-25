<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'is_read',
    ];

    // Define the relationship with the User model (assuming you have a User model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
