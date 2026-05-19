<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['event_id', 'name', 'email', 'rsvp_status', 'dietary_preferences'])]
class Guest extends Model
{
    //
}
