<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'company_id',
        'account_id',
        'paid_at',
        'amount',
        'currency_code',
        'currency_rate',
        'vendor_id',
        'description',
        'category_id',
        'payment_method',
        'reference',
        'attachment',
        'reconciled',
        'parent_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scopeIsNotTransfer($query)
    {
        return $query->where('category_id', '<>', Category::transfer());
    }
    
}
