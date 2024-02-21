<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalExpense extends Model
{
    use HasFactory;
    protected $table = 'personal_expense';
    public $timestamps = false;
    protected $fillable = [
        'id_cash',
        'id_user',
    ];
}
