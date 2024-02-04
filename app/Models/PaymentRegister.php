<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRegister extends Model
{
    use HasFactory;
    protected $table = 'payment_methods';
    public $timestamps = false;
    protected $fillable = [
        'id_cash',
        'payment_type',
    ];
}
