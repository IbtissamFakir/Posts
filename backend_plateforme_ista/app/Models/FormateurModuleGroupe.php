<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormateurModuleGroupe extends Model
{
    use HasFactory;

    protected $fillable = [
        'formateur_id',
        'module_id',
        'groupe_id',
    ];

    public function formateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }


}
