<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomGallery;

class HomeController extends Controller
{
    public function index()
    {
        $roomGalleries = RoomGallery::all();
        return view('home.gallery', compact('roomGalleries'));
    }

    public function roomList()
    {
        $rooms = Room::all()->sortBy('room_number');  // Sort rooms by room_number
        $roomGalleries = RoomGallery::all();

        // Map rooms to their corresponding gallery based on matching `room_number` and `name`
        $roomsWithGalleries = $rooms->map(function ($room) use ($roomGalleries) {
            $gallery = $roomGalleries->firstWhere('name', $room->room_number);
            $room->gallery_image = $gallery ? $gallery->image_path : null;
            return $room;
        });

        return view('home.room', compact('roomsWithGalleries'));
    }
}
