<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'payment_id', 'payer_id', 'payer_email', 'amount', 'currency', 'payment_status']; // Asegúrate de tener los campos adecuados aquí

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
