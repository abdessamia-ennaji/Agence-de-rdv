<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RdvStatus extends Model
{
    use HasFactory;
    protected $fillable = ['rdv_id', 'status'];
}
