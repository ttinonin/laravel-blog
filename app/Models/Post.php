<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "body",
        "user_id"
    ];

    // Esta funcao define a relação por FK de user e posts
    // Podendo acessar $post->user->(algum parametro de user)
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
