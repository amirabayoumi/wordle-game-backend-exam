<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIconRequest;
use App\Http\Requests\UpdateIconRequest;
use App\Models\Icon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IconController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $icons = Icon::all();
        return view('icons.index', compact('icons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('icons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIconRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('icons', 'public');
            $validated['image'] = $path;
        }

        Icon::create($validated);

        return redirect()->route('icons.index')->with('success', 'Icon added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Icon $icon)
    {
        return view('icons.show', compact('icon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Icon $icon)
    {
        return view('icons.edit', compact('icon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIconRequest $request, Icon $icon)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($icon->image && Storage::disk('public')->exists($icon->image)) {
                Storage::disk('public')->delete($icon->image);
            }
            $path = $request->file('image')->store('icons', 'public');
            $validated['image'] = $path;
        }

        $icon->update($validated);

        return redirect()->route('icons.index')->with('success', 'Icon updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Icon $icon)
    {
        // Delete image file if exists
        if ($icon->image && Storage::disk('public')->exists($icon->image)) {
            Storage::disk('public')->delete($icon->image);
        }
        $icon->delete();

        return redirect()->route('icons.index')->with('success', 'Icon deleted');
    }
}
