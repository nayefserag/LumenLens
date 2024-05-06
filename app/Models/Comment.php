<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['content' , 'post_id', 'author', 'deleted_at', 'deleted_by', 'deleted_reason', 'like_count', 'dislike_count'];
    

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
