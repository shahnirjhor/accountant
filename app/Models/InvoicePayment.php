<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }

    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (double) $value;
    }

    public function scopePaid($query)
    {
        return $query->sum('amount');
    }

}
