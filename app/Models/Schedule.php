<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'horario';

    protected $fillable = [
        'dia1',
        'dia2',
        'festivos',
        'hora_inicio',
        'hora_final',
    ];
}
