<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KnowledgeArticle;

class ClientKnowledgeBaseController extends Controller
{
    public function index(Request $request)
    {
        $query = KnowledgeArticle::where('is_published', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->latest()->paginate(9);
        $categories = KnowledgeArticle::where('is_published', true)->distinct()->pluck('category')->toArray();

        return view('client.knowledge.index', compact('articles', 'categories'));
    }

    public function show($slug)
    {
        $article = KnowledgeArticle::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $article->increment('views_count');

        $relatedArticles = KnowledgeArticle::where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->where('is_published', true)
            ->take(3)
            ->get();

        return view('client.knowledge.show', compact('article', 'relatedArticles'));
    }
}
