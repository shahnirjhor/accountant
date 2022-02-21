<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTotal extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'invoice_id',
        'code',
        'name',
        'amount',
        'sort_order'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }
}
