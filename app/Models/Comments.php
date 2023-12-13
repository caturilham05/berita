<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'sub_comment_id',
        'sub_comment_type',
        'name',
        'email',
        'comment',
        'like',
        'dislike',
        'created_at',
        'updated_at',
    ];
    public $timestamps = false;

    public function content()
    {
        return $this->belongsTo(Contents::class);
    }

    public function replies()
    {
        return $this->hasMany(Comments::class, 'sub_comment_id');
    }
}
