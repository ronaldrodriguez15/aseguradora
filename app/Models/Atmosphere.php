<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atmosphere extends Model
{
    use HasFactory;

    protected $table = 'atmosphere';

    protected $fillable = [
        'name',
        'key'
    ];
}
