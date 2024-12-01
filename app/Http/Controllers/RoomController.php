<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $rooms = Room::query()
            ->when($search, function ($query, $search) {
                $query->where('room_number', 'like', "%{$search}%");
            })
            ->orderBy('stay_type', 'asc') // Add this line to sort alphabetically
            ->paginate(10);

        return view('rooms.index', ['rooms' => $rooms]);
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'room_number' => ['required', 'string', 'max:50'],
            'room_type' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'pax' => ['required', 'string'],
            'stay_type' => ['required', 'in:day tour,overnight'],
        ]);

        Room::create($fields);

        return redirect()->route('rooms.index')->with('success', 'The room has been added');
    }

    public function show(Room $room)
    {
        return view('rooms.show', ['room' => $room]);
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', ['room' => $room]);
    }

    public function update(Request $request, Room $room)
    {
        $fields = $request->validate([
            'room_number' => ['required', 'string', 'max:50'],
            'room_type' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'pax' => ['required', 'string'],
            'stay_type' => ['required', 'in:day tour,overnight'],
        ]);

        $room->update($fields);

        return redirect()->route('rooms.index')->with('success', 'The room has been updated');
    }


    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')
            ->with('deleted', 'Room deleted successfully.');
    }
}
