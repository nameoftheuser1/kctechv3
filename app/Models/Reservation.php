<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'reservation_room')
            ->withTimestamps();
    }
}
