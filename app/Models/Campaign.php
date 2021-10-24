<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $hidden = [];

    public $timestamps = false;

    protected $dates = [
        'date_from',
        'date_to',
    ];
}
