<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSigned extends Model
{
    use HasFactory;

    protected $table = 'documents_signed';

    protected $fillable = [
        'file_name',
        'signed_id',
        'expires',
        'document_path',
        'inabilitiy_id'
    ];
}
