<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentsChilds extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id_par',
        'sub_comment_id_child',
        'name',
        'email',
        'comment',
        'like',
        'dislike',
        'created_at',
        'updated_at',
    ];
    public $timestamps = false;
}
