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
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
