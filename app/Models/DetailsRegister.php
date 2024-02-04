<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsRegister extends Model
{
    use HasFactory;
    protected $table = 'cash_details';
    public $timestamps = false;
    protected $fillable = [
        'id_reg',
        'amount',
        'date',
        'id_user',
        'id_store',
    ];
}
