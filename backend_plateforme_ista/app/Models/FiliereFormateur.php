<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiliereFormateur extends Model
{
    use HasFactory;

    protected $fillable = [
        'filiere_id',
        'utilisateur_id',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function formateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }


}
