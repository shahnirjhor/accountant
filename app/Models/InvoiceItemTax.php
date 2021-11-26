<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItemTax extends Model
{
    use HasFactory;

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }
}
