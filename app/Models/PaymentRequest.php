<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function userRequested()
    {
        return $this->hasOne(User::class, 'id', 'user_requested');
    }

    public function userCreated()
    {
        return $this->hasOne(User::class, 'id', 'user_created');
    }

    public function invoices()
    {
        return $this->hasMany(PaymentRequestInvoice::class, 'payment_request_code', 'code');
    }
}
