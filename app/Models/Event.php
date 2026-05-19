<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'name', 'date', 'location', 'guest_count', 'theme', 'budget'])]
class Event extends Model
{
    //
}
