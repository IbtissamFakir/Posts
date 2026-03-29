<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enregistrement extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'utilisateur_id',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }


}
