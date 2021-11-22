<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

}
