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

    // 🔹 Each signalement belongs to one commentaire
    public function commentaire()
    {
        return $this->belongsTo(Commentaire::class);
    }

    // 🔹 Each signalement is made by one utilisateur (the one who reported it)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
