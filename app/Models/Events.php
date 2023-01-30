<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $fillable = ['days', 'start_date', 'end_date', 'start_at', 'end_at', 'meeting_url', 'title', 'description'];
}
