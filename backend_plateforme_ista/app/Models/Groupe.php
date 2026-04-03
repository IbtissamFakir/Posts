<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'niveau',
        'effectif',
        'filiere_id',
    ];

    // Each group belongs to one filiere
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    // Many-to-many: group can have many formateurs
    public function formateurs()
    {
        return $this->belongsToMany(User::class, 'formateur_module_groupe', 'groupe_id', 'user_id')
            ->where('role', 'formateur')
            ->withTimestamps();
    }

    // One-to-many: group has many students
    public function stagiaires()
    {
        return $this->hasMany(User::class)->where('role', 'stagiaire');
    }
}