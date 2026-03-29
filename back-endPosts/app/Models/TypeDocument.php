<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];

    // 🔹 A type document can be associated with many document
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
