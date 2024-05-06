<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'content', 'author', 'comment_ids'];
    protected $casts = [
        'comment_ids' => 'array'
    ];

    public function comments()
    {
        return Comment::findMany($this->comment_ids);
    }
}
