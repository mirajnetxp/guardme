<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    //
    protected $table = 'payment_requests';
    public $timestamps = false;
}
