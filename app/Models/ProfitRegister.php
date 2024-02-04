<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitRegister extends Model
{
    use HasFactory;
    protected $table = 'profits';
    public $timestamps = false;
    protected $fillable = [
        'id_reg',
        'payments',
        'sale',
        'expense',
        'profit',
        'date',
        'id_user',
        'id_store',
        'balance',
    ];
}
