<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'email',
        'tax_number',
        'phone',
        'address',
        'website',
        'currency_code',
        'enabled',
        'reference'
    ];

    public $sortable = [
        'name',
        'email',
        'phone',
        'enabled'
    ];

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
