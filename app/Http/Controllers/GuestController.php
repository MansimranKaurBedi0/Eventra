<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestInvitation;

class GuestController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255', // Make email required
            'rsvp_status' => 'required|in:pending,yes,no',
        ]);

        $guest = $event->guests()->create($validated);

        // Send Email Invitation (if they are pending)
        if ($guest->rsvp_status === 'pending') {
            Mail::to($guest->email)->send(new GuestInvitation($guest, $event));
        }

        return back()->with('success', 'Guest added & invitation sent!');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return back()->with('success', 'Guest removed.');
    }

    public function updateRsvp(Request $request, Guest $guest)
    {
        // Ensure this guest belongs to the logged in user
        if ($guest->email !== auth()->user()->email) {
            abort(403);
        }

        $validated = $request->validate([
            'rsvp_status' => 'required|in:pending,yes,no',
            'dietary_preferences' => 'nullable|string|max:255',
        ]);

        $guest->update($validated);

        return back()->with('success', 'Your RSVP has been updated!');
    }
}
