<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use Searchable;
    use HasFactory;

    protected $fillable = [
        "title",
        "body",
        "user_id"
    ];

    public function toSearchableArray() {
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }

    // Esta funcao define a relação por FK de user e posts
    // Podendo acessar $post->user->(algum parametro de user)
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
