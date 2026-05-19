<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['event_id', 'vendor_profile_id', 'agreed_price', 'status'])]
class Booking extends Model
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function vendorProfile()
    {
        return $this->belongsTo(VendorProfile::class);
    }
}
