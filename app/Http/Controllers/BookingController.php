<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'vendor_profile_id' => 'required|exists:vendor_profiles,id',
            'agreed_price' => 'required|numeric|min:0',
        ]);

        Booking::create($validated);

        return back()->with('success', 'Booking request sent to vendor!');
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        $booking->update(['status' => $request->status]);

        if ($request->status === 'approved') {
            Expense::create([
                'event_id' => $booking->event_id,
                'title' => 'Vendor: ' . $booking->vendorProfile->company_name,
                'amount' => $booking->agreed_price,
                'category' => $booking->vendorProfile->category,
            ]);
        }

        return back()->with('success', 'Booking ' . $request->status . ' successfully!');
    }
}
