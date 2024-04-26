<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'slug',
        'color',
        'category_id',
        'content',
        'thumbnail',
        'tags', 
        'published',
    ];

    protected $casts = [
        'tag' => 'array',
    ];
}
