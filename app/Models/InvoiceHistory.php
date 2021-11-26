<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceHistory extends Model
{
    use HasFactory;

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

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function status()
    {
        return $this->belongsTo(InvoiceStatus::class, 'status_code', 'code');
    }
}
