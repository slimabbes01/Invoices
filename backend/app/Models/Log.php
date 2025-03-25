<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user_id',
        'action',
    ];

    // Define the relationship with the User model (assuming you have a User model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
