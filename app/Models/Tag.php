<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory,HasUlids;

    protected $fillable = ['title'];


    public function posts()
    {
        return  $this->hasMany(Post::class);
    }
}
