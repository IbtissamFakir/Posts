<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'commentaire_id',
        'user_id',
    ];

    /**
     * Relation avec le commentaire signalé
     */
    public function commentaire()
    {
        return $this->belongsTo(Commentaire::class);
    }

    /**
     * Relation avec l'utilisateur qui a signalé
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
