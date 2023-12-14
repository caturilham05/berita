<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contents extends Model
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

    public function comment()
    {
        return $this->morphMany(Comments::class, 'content')->whereNull('sub_comment_id');
    }

    public function getImages()
    {
        $images        = $this->select('id', 'title', 'image_thumb', 'timestamp' ,'images')->where('images', '<>', '')->orderBy('id', 'desc')->get();
        $images_decode = [];
        if (!empty($images))
        {
            foreach ($images as $key => $value)
            {
                $value->images = json_decode($value->images, 1);
                $images_decode[] = $value;
            }
        }

        return $images_decode;
    }
}
