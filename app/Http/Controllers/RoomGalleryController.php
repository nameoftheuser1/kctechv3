<?php

namespace App\Http\Controllers;

use App\Models\RoomGallery;
use Illuminate\Http\Request;

class RoomGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query for gallery images, applying search filter if provided
        $roomGallery = RoomGallery::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
            ->paginate(9); // Paginate results to display in the grid

        return view('galleries.index', compact('roomGallery', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('galleries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // max:10MB
            'existing_image' => 'nullable|string',
        ]);

        // Initialize variables for the image path
        $imagePath = null;

        // Handle uploaded image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('img', 'public');
        } elseif ($request->filled('existing_image')) {
            $imagePath = $request->input('existing_image');
        }

        // Create a new gallery record
        RoomGallery::create([
            'name' => $request->input('name'),
            'image_path' => $imagePath,
        ]);

        // Redirect with a success message
        return redirect()->route('galleries.index')->with('success', 'Gallery image added successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(RoomGallery $roomGallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomGallery $roomGallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomGallery $roomGallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomGallery $roomGallery)
    {
        //
    }
}
