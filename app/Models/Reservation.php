<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'pax',
        'contact',
        'car_unit_plate_number',
        'check_in',
        'check_out',
        'status',
        'total_amount',
        'is_commissioned',
        'checkout_time',
    ];

    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 2, '.', ',');
    }

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'checkout_time' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'reservation_room')
            ->withTimestamps();
    }

    public function salesReports(): HasMany
    {
        return $this->hasMany(SalesReport::class);
    }
}
