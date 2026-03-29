<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'note',
        'type_evaluation_id',
        'module_id',
        'etudiant_id',
    ];
    public function typeEvaluation()
    {
        return $this->belongsTo(TypeEvaluation::class);
    }
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
