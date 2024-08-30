<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type_entity',
        'area',
        'official_in_charge',
        'employment',
        'phone',
        'mobile',
        'email',
        'address',
        'status'
    ];
}
