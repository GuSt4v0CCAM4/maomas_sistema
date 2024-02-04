<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;
    protected $table = 'sales_amount';
    public $timestamps = false;
    protected $fillable = [
        'id_reg',
        'amount',
        'date',
        'id_user',
        'id_store',
    ];
}
