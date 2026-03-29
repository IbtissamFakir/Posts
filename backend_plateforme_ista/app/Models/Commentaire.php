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
        'utilisateur_id',
        'post_id',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class );
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }

    
}
