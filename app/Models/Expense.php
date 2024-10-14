<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_description',
        'amount',
        'date_time',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];
}
