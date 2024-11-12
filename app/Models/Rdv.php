<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    use HasFactory;


    protected $table = 'rdv';
    protected $fillable = [
        'prenom',
        'nom',
        'telephone',
        'rdv_date',
        'rdv_time',
        'adresse',
        'ville',
        'commentaire',
    ];

    public function status() {
        return $this->hasOne(RdvStatus::class, 'rdv_id');  // Correcting the foreign key reference
    }
}




