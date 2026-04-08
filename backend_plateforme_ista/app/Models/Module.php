<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'nbrCC',
        'coeficient',
        'masse_horaire',
        'type_module',
        'filiere_id',
    ];

    // 🔹 Each module belongs to one filiere
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    // 🔹 Many-to-many: module can be taught by many formateurs
    public function formateurs()
    {
        return $this->belongsToMany(User::class, 'formateur_module_groupe', 'module_id', 'user_id')
            ->where('role', 'formateur')
            ->withPivot('groupe_id')
            ->withTimestamps();
    }

    // 🔹 Many-to-many: module can be taught in many groupes
    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'formateur_module_groupe', 'module_id', 'groupe_id')
            ->withPivot('user_id')
            ->withTimestamps();
    }

    // 🔹 If modules have evaluations (tests, exams, etc.)
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}