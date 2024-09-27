<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docuuments extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'estasseguro_document',
        'libranza_document',
        'debito_document',
    ];
}
