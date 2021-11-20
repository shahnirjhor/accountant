<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $fillable = [
        'company_id',
        'account_id',
        'paid_at',
        'amount',
        'currency_code',
        'currency_rate',
        'customer_id',
        'description',
        'category_id',
        'payment_method',
        'reference',
        'attachment',
        'reconciled',
        'parent_id'
    ];

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
