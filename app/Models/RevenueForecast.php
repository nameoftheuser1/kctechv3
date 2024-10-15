<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueForecast extends Model
{
    use HasFactory;

    protected $fillable = ['forecast_date', 'predicted_revenue'];
}
