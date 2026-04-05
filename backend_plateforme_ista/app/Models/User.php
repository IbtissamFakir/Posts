<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_complet', 
        'email',
        'password',
        'role',
        'statut',
        'CEF_matricule',
        'photo',
        'groupe_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 🔹 Students: each belongs to one groupe
    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    // 🔹 Students: each belongs to one filiere (direct foreign key)
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    // 🔹 Formateurs: many-to-many with filieres via pivot
    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'filiere_formateur')
            ->withTimestamps();
    }

    // 🔹 Formateurs: many-to-many with groupes via pivot
    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'formateur_module_groupe')
            ->withPivot('module_id')
            ->withTimestamps();
    }

    // 🔹 Formateurs: many-to-many with modules via pivot
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'formateur_module_groupe')
            ->withPivot('groupe_id')
            ->withTimestamps();
    }

    // A USER CAN PASS MANY EVALUATIONS (as a student)
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }


    // q user can create many posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    //a user can like many posts
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // a user can validate many posts (if they are an admin)
    public function validatedPosts()
    {
        return $this->hasMany(Post::class, 'validated_by')
            ->where('statut', 'validated');
    }

    // a user can reject many posts (if they are an admin)
    public function rejectedPosts()
    {
        return $this->hasMany(Post::class, 'rejected_by')
            ->where('statut', 'rejected');
    }

    // a user can write many comments
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    // a user can make many signalements
    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }

    // a user can create many annonces
    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    // All enregistrements made by this user
    public function enregistrements()
    {
        return $this->hasMany(Enregistrement::class);
    }

    // Only enregistrements of posts
    public function enregistrementsPosts()
    {
        return $this->hasMany(Enregistrement::class)->whereNotNull('post_id');
    }

    // Only enregistrements of annonces
    public function enregistrementsAnnonces()
    {
        return $this->hasMany(Enregistrement::class)->whereNotNull('annonce_id');
    }
}
