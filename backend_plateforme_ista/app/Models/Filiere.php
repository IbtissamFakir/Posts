<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'niveau',
        'code',
        'secteur_id',
    ];

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function groupes()
    {
        return $this->hasMany(Groupe::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Many-to-many: filiere can have many formateurs
    public function formateurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'filiere_formateur')
                    ->where('role', 'formateur')
                    ->withTimestamps();
    }
}
