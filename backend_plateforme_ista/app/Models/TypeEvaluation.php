<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];

    // 🔹 A type evaluation can be associated with many evaluatio
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
