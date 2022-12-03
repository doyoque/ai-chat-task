<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * customer has many purchase_transactions.
     */
    public function transactions()
    {
        return $this->hasMany(PurchaseTransaction::class);
    }
}
