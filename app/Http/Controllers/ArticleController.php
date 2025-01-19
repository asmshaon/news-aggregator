<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Article::with(['author:id,name', 'source:id,name', 'category:id,name']);

        // Search by keyword in title or description
        if ($request->has('keyword') && !empty($request->string('keyword'))) {
            $keyword = $request->string('keyword');

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Search by date range (published_at)
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('published_at', [
                $request->get('start_date'),
                $request->get('end_date')
            ]);
        }

        // Search by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        // Search by source
        if ($request->has('source_id')) {
            $query->where('source_id', $request->integer('source_id'));
        }

        // Search by author
        if ($request->has('author_id')) {
            $query->where('source_id', $request->integer('author_id'));
        }

        $query->orderBy('published_at', 'DESC');

        $articles = $query->select([
            'id',
            'title',
            'description',
            'published_at',
            'source_id',
            'author_id',
            'category_id',
            'url',
            'image'
        ])->paginate($request->integer('per_page', 10));

        return response()->json($articles);
    }
}
