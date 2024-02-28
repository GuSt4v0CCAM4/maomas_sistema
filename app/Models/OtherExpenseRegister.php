<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherExpenseRegister extends Model
{
    use HasFactory;
    protected $table = 'expense_others';
    public $timestamps = false;
    protected $fillable = [
        'id_expense',
        'details',
    ];
}
