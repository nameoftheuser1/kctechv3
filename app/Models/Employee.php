<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'salary',
        'payout_date'
    ];

    protected $casts = [
        'payout_date' => 'date',
    ];

}
