<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorProfile;

class VendorProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = VendorProfile::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('max_price')) {
            $query->where('starting_price', '<=', $request->max_price);
        }

        $vendors = $query->paginate(12);

        return view('vendors.index', compact('vendors'));
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'starting_price' => 'nullable|numeric|min:0',
        ]);

        $profile = VendorProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return back()->with('success', 'Your Vendor Profile has been updated!');
    }
}
