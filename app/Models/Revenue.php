<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DateTime;

class Revenue extends Model
{
    use DateTime;
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

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function scopeIsTransfer($query)
    {
        return $query->where('category_id', '=', Category::transfer());
    }

    public function scopeIsNotTransfer($query)
    {
        return $query->where('category_id', '<>', Category::transfer());
    }


}
