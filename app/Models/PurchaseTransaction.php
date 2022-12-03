<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    use HasFactory;

    /**
     * purchase_transactions belongs to customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
