<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'date_publication',
        'likes',
        'status',
        'post_id',
        'user_id',
    ];

    protected $casts = [
        'date_publication' => 'datetime',
    ];

    /**
     * Relation avec le post
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relation avec l'utilisateur (propriétaire du commentaire)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les signalements
     */
    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }

    /**
     * Vérifier si le commentaire est masqué
     */
    public function estMasque()
    {
        return $this->status === 'masque';
    }

    /**
     * Vérifier si le commentaire doit être masqué
     */
    public function verifierEtMasquer()
    {
        $nombreSignalements = $this->signalements()->count();

        if ($nombreSignalements >= 5 && $this->status !== 'masque') {
            $this->status = 'masque';
            $this->save();
        }
    }
}
