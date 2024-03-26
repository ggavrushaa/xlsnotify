<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function contracts()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function salesInvoices()
    {
        return $this->hasMany(OrderSalesInvoice::class);
    }

}
