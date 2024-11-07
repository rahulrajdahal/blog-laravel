<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(required={"title"})
 */
class Tag extends Model
{
    use HasFactory, HasUlids;

    /**
     * Title for the tag.
     *
     * @var string
     *
     * @OA\Property(format="string", example="Example Tag")
     */
    public $title;

    protected $fillable = ['title'];


    public function posts()
    {
        return  $this->hasMany(Post::class);
    }
}
