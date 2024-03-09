<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseProvider extends Model
{
    use HasFactory;
    protected $table = 'expense_provider';
    public $timestamps = false;
    protected $fillable = [
        'id_expense',
        'provider',
    ];
}
