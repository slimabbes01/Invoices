<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'users'; // Specify the table name if necessary

    protected $fillable = [
        'name',
        'username',  // Ensure 'username' is in this array
        'email',
        'password',
        'profil',
        'direction',
        'isactive',
        'image',
        'phone',
        'created_by',
    ];

    protected $hidden = [
        'password', // Hide the password attribute
        'remember_token',
    ];

    // Define any necessary relationships
    public function fournisseurs()
    {
        return $this->hasMany(Fournisseur::class, 'created_by'); // Adjust this according to your database structure
    }
}
