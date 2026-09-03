<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeArticle extends Model
{
    protected $table = 'knowledge_articles';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'icon',
        'summary',
        'content',
        'views_count',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'views_count' => 'integer',
    ];
}
