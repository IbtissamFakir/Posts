<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'date_publication',
        'image_path',
        'file_path',
        'statut',
        'user_id',
    ];

    // 🔹 Each post belongs to one user (author)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 A post can have many comments
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    // a post must be validated by an admin before being visible to others
    public function validateur()
    {
        return $this->belongsTo(User::class, 'validated_by')
            ->where('role', 'admin');
    }

    // a post can be rejected by an admin, in which case it will have a 'rejected_by' field
    public function rejecteur()
    {
        return $this->belongsTo(User::class, 'rejected_by')
            ->where('role', 'admin');
    }

    // 🔹 A post can have many enregistrements (saved by users)
    public function enregistrements()
    {
        return $this->hasMany(Enregistrement::class);
    }

    //  A post can have many like
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
