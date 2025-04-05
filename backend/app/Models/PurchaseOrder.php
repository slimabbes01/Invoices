<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    // Specify the table name if it does not follow Laravel's naming conventions
    protected $table = 'purchase_order'; // Adjust this to your actual table name

    // Define the fillable fields
    protected $fillable = [
        'num_commande',
        'iderp',
        'delai_paiement',
        'created_by',
    ];

    // Define any necessary relationships
    // Example: if a purchase order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
