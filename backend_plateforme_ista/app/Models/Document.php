<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'fichier_path',
        'statut',
        'type_document_id',
        'module_id',
        'user_id',
    ];

    public function typeDocument()
    {
        return $this->belongsTo(TypeDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
{
    return $this->belongsTo(Module::class);
}
}
