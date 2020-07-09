<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'featured', 'category_id', 'user_id', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag');
    }
}
