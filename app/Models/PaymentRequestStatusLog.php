<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequestStatusLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function userCreated()
    {
        return $this->hasOne(User::class, 'id', 'user_created');
    }
}
