<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['event_id', 'title', 'amount', 'category'])]
class Expense extends Model
{
    //
}
