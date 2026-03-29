<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];

    // 🔹 A secteur can have many filiere
    public function filieres()
    {
        return $this->hasMany(Filiere::class);
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }
}
