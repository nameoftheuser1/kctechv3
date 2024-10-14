<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type',
        'price',
        'pax',
        'stay_type',
    ];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_room')
                    ->withTimestamps();
    }
}
