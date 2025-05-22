<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // Store a new menu item with an image
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:breakfast,lunch,refreshment', // Add this
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Store the image in public storage
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $menu = Menu::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category, // Add this
            'price' => $request->price,
            'image' => $imagePath // Save the image path to the database
        ]);

        return response()->json([
            'message' => 'Menu created successfully',
            'menu' => $menu
        ], 201);
    }

    // Get all menu items
    public function index()
    {
        $menus = Menu::all();
        return response()->json($menus);
    }
    // Get a single menu item by id
    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return response()->json($menu);
    }
    // Update a menu item by id with a new image
    public function update(Request $request, $id)
{
    $menu = Menu::findOrFail($id);

    $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|required|string',
        'category' => 'required|in:breakfast,lunch,refreshment', // Add this
        'price' => 'sometimes|required|numeric',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // If new image is uploaded, delete the old one and update it
    if ($request->hasFile('image')) {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        // Store new image
        $imagePath = $request->file('image')->store('uploads', 'public');
        $menu->image = $imagePath;
    }

    // Update other fields
    $menu->update($request->except('image'));

    return response()->json([
        'message' => 'Menu updated successfully',
        'menu' => $menu
    ], 200);
}

    // Delete a menu item by id
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // Delete image from public storage
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return response()->json(['message' => 'Menu deleted successfully']);
    }
}
