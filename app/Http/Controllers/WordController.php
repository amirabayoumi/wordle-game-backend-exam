<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\WordHelper;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $words = Word::all();
        return view('words.index', compact('words'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('words.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'word' => 'required|string|size:5|unique:words',
            'schedule_at' => 'required|date|after_or_equal:today|unique:words,schedule_at'
        ], [
            'word.size' => 'The word must be exactly 5 characters.',
            'word.unique' => 'This word already exists. Please choose a different word.',
            'schedule_at.after_or_equal' => 'The date must be today or in the future.',
            'schedule_at.unique' => 'This date is already taken. Please choose another date.'
        ]);

        if (!WordHelper::isEnglishWord($request->word)) {
            return back()->withErrors(['word' => 'This word is not a valid English 5-letter word.'])->withInput();
        }

        // Create new word (convert to uppercase)
        $word = Word::create([
            'word' => strtoupper($request->word),
            'schedule_at' => $request->schedule_at,
        ]);

        return redirect()->route('words.index')
            ->with('success', 'Word created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Word $word)
    {
        return view('words.show', compact('word'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Word $word)
    {
        return view('words.edit', compact('word'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Word $word)
    {
        // Validate the request data
        $request->validate([
            'word' => 'required|string|size:5|unique:words,word,' . $word->id,
            'schedule_at' => 'required|date|after_or_equal:today|unique:words,schedule_at,' . $word->id
        ], [
            'word.size' => 'The word must be exactly 5 characters.',
            'word.unique' => 'This word already exists. Please choose a different word.',
            'schedule_at.after_or_equal' => 'The date must be today or in the future.',
            'schedule_at.unique' => 'This date is already taken. Please choose another date.'
        ]);

        // Check if the word is a valid English 5-letter word
        if (!WordHelper::isEnglishWord($request->word)) {
            return back()->withErrors(['word' => 'This word is not a valid English 5-letter word.'])->withInput();
        }

        // Update the word (convert to uppercase)
        $word->update([
            'word' => strtoupper($request->word),
            'schedule_at' => $request->schedule_at,
        ]);

        return redirect()->route('words.index')
            ->with('success', 'Word updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Word $word)
    {
        $word->delete();

        return redirect()->route('words.index')
            ->with('success', 'Word deleted successfully.');
    }
}
