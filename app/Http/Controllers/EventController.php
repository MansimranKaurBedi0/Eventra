<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'guest_count' => 'required|integer|min:1',
            'theme' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        
        Event::create($validated);

        return redirect()->route('dashboard')->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        // Ensure the user owns this event
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        return view('events.show', compact('event'));
    }
}
