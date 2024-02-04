<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseRegister extends Model
{
    use HasFactory;
    protected $table = 'expenses';
    public $timestamps = false;
    protected $fillable = [
        'id_cash',
        'expense_type',
    ];
}
