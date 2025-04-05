<?php

// app/Models/Fournisseur.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $table = 'fournisseur'; // Specify the table name if it doesn't follow Laravel's naming convention
    protected $fillable = [
        'iderp',
        'name',
        'idfiscale',
        'adresse',
        'nationnalite',
        'created_by'
    ];

    // Define relationships, if any
    public function factures()
    {
        return $this->hasMany(Facture::class);
    }
}
