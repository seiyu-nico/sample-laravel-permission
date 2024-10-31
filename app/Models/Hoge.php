<?php

namespace App\Models;

use Database\Factories\HogeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hoge extends Model
{
    /** @use HasFactory<HogeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
