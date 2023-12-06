<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaguesSeasons extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'league_id', 'league_id_origin', 'year', 'start_date', 'end_date', 'current'];
    public $timestamps  = false;
}
