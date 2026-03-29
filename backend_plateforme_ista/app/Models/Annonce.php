<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date_publication',
        'statut',
        'utilisateur_id',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    // a user can save many annonces (enregistrements)
    public function enregistrements()
    {
        return $this->hasMany(Enregistrement::class);
    }

}
