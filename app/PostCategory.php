<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $fillable = ['name', 'icon'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'post_category');
    }
}
