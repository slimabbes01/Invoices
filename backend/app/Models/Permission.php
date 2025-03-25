<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    // Specify the attributes that are mass assignable
    protected $fillable = [
        'name',
        'description',
    ];

    // Define any relationships if necessary
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
