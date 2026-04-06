<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//
//class Post extends Model
//{
//    use HasFactory;
//
//    protected $fillable = [
//        'titre',
//        'content',
//        'image',
//        'date_publication',
//        'status',
//        'utilisateur_id',
//        'validated_by',
//        'rejected_by',
//    ];
//
//    // 🔹 Each post belongs to one user (author)
//    public function utilisateur()
//    {
//        return $this->belongsTo(Utilisateur::class);
//    }
//
//    // 🔹 A post can have many comments
//    public function commentaires()
//    {
//        return $this->hasMany(Commentaire::class);
//    }
//
//    // a post must be validated by an admin before being visible to others
//    public function validateur()
//    {
//        return $this->belongsTo(Utilisateur::class, 'validated_by')
//            ->where('role', 'admin');
//    }
//
//    // a post can be rejected by an admin, in which case it will have a 'rejected_by' field
//    public function rejecteur()
//    {
//        return $this->belongsTo(Utilisateur::class, 'rejected_by')
//            ->where('role', 'admin');
//    }
//
//    // 🔹 A post can have many enregistrements (saved by users)
//    public function enregistrements()
//    {
//        return $this->hasMany(Enregistrement::class);
//    }
//
//}


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'content',
        'images',           //  stocker le tableau JSON
        'fichiers',         // Ajouté : pour stocker les documents JSON
        'date_publication',
        'statut',
        'utilisateur_id',
        'validated_by',
        'rejected_by',
    ];


    protected $casts = [
        'images' => 'array',
        'fichiers' => 'array',
        'date_publication' => 'datetime',
    ];

    // 🔹 Chaque post appartient à un auteur (utilisateur)
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // 🔹 Un post peut avoir plusieurs commentaires
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    // Un post est validé par un admin
    public function validateur()
    {
        return $this->belongsTo(Utilisateur::class, 'validated_by')
            ->where('role', 'admin');
    }

    // Un post peut être rejeté par un admin
    public function rejecteur()
    {
        return $this->belongsTo(Utilisateur::class, 'rejected_by')
            ->where('role', 'admin');
    }

    // 🔹 Un post peut être enregistré par plusieurs utilisateurs
    public function enregistrements()
    {
        return $this->hasMany(Enregistrement::class);
    }
}
