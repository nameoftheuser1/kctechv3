<?php

namespace App\Http\Controllers;

use App\Models\RoomGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            'existing_image' => 'nullable|string', // no need to check if it exists in the database
        ]);

        $imageName = null;

        // Handle new image upload if it's selected
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Get the original file name
            $imageName = $image->getClientOriginalName();

            // Move the file to the public/img directory
            $image->move(public_path('img'), $imageName);

            // Ensure the image path stored in the database includes the "img/" prefix
            $imageName = 'img/' . $imageName;
        } elseif ($request->has('existing_image')) {
            // If an existing image is selected, use the provided image path (without adding "img/")
            $imageName = $request->input('existing_image');
        }

        // Create a new gallery record
        RoomGallery::create([
            'name' => $request->input('name'),
            'image_path' => $imageName, // Store the path (either new or existing image)
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

    public function destroy($id)
    {
        // Load the RoomGallery by its ID
        $roomGallery = RoomGallery::find($id);

        // Check if the record exists
        if (!$roomGallery) {
            return redirect()->route('galleries.index')->with('error', 'Room Gallery not found.');
        }

        $filePath = $roomGallery->image_path;

        // Check if image_path is not null and the file exists
        if ($filePath) {
            if (Storage::disk('local')->exists($filePath)) {
                // Delete the file
                Storage::disk('local')->delete($filePath);
            }
        }

        // Now delete the RoomGallery record from the database
        try {
            $roomGallery->delete();
        } catch (\Exception $e) {
            return redirect()->route('galleries.index')->with('error', 'Failed to delete Room Gallery.');
        }

        // Return a redirect with a success message
        return redirect()->route('galleries.index')->with('success', 'Room Gallery and associated image deleted successfully.');
    }
}
