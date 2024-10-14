<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'check_in',
        'check_out',
        'status',
        'contact',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'reservation_room')
            ->withTimestamps();
    }
}
