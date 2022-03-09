<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['company_id', 'name', 'number', 'currency_code', 'opening_balance', 'bank_name', 'bank_phone', 'bank_address', 'enabled'];


    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }
    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getBalanceAttribute()
    {
        // Opening Balance
        $total = $this->opening_balance;

        // Sum Incomes
        //$total += $this->invoice_payments()->sum('amount') + $this->revenues()->sum('amount');

        // Subtract Expenses
        //$total -= $this->bill_payments()->sum('amount') + $this->payments()->sum('amount');

        return $total;
    }
}
