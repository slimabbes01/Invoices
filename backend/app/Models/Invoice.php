<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_name',
        'buyer_name',
        'buyer_address',
        'buyer_tax_id',
        'buyer_commercial_register',
        'buyer_phone',
        'issue_date',
        'product_or_service_list',
        'total_amount',
        'payment_terms',
        'pdf_upload',
    ];

    protected $casts = [
        'product_or_service_list' => 'array', // Permet de récupérer les produits sous forme de tableau
        'issue_date' => 'date',
    ];
}
