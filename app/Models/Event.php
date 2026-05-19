<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'name', 'date', 'location', 'guest_count', 'theme', 'budget'])]
class Event extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
