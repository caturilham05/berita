<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leagues extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'id_origin', 'code_countries', 'name', 'type', 'logo'];
    public $timestamps  = false;
}
