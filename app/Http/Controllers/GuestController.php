<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Guest;

class GuestController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'rsvp_status' => 'required|in:pending,yes,no',
            'dietary_preferences' => 'nullable|string|max:255',
        ]);

        $event->guests()->create($validated);

        return back()->with('success', 'Guest added successfully!');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return back()->with('success', 'Guest removed.');
    }
}
