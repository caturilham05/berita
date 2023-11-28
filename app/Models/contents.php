<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contents extends Model
{
    use HasFactory;
    protected $fillable = [
        'tag_ids',
        'cat_ids',
        'title',
        'intro',
        'image',
        'image_thumb',
        'images',
        'content',
        'is_active',
        'timestamp',
        'url',
    ];
    public $timestamps = false;
}
