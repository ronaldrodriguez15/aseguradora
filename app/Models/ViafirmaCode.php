<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViafirmaCode extends Model
{
    use HasFactory;

    protected $table = 'viafirma_codes';

    protected $fillable = [
        'inability_id',
        'message_code',
        'template_code',
    ];
}
