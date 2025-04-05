<?php

// app/Models/Facture.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $table = 'factures';

    protected $fillable = [
        'facture_type',
        'bordereau',
        'fournisseur',
        'dossier',
        'structure',
        'direction',
        'date_fact',
        'period_conso',
        'num_fact',
        'devise',
        'montant',
        'objet',
        'num_po',
        'status',
        'factname',
        'pathpdf',
        'datereception',
        'created_by',
    ];
}

