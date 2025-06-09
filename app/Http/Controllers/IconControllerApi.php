<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \App\Models\Icon;

class IconControllerApi extends Controller

{
    /**
     * Display a listing of the icons.
     */
    public function index()
    {
        $icons = Icon::all();
        return response()->json($icons);
    }

    /**
     * Store a newly created icon in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('icons', 'public');
            $validated['image'] = $path;
        }

        $icon = \App\Models\Icon::create($validated);

        return response()->json([
            'success' => true,
            'icon' => $icon,
            'image_url' => asset('storage/' . $icon->image),
        ]);
    }
}
