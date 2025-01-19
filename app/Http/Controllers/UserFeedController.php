<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Http\JsonResponse;

class UserFeedController extends Controller
{
    public function index(): JsonResponse
    {
        $preference = UserPreference::where('user_id', auth()->id())->first();

        $query = Article::with(['author:id,name', 'source:id,name', 'category:id,name']);

        if ($preference->preferred_sources || $preference->preferred_categories || $preference->preferred_authors) {
            $query->where(function ($query) use ($preference) {
                if ($preference->preferred_sources) {
                    $query->orWhereIn('source_id', $preference->preferred_sources);
                }

                if ($preference->preferred_categories) {
                    $query->orWhereIn('category_id', $preference->preferred_categories);
                }

                if ($preference->preferred_authors) {
                    $query->orWhereIn('author_id', $preference->preferred_authors);
                }
            });
        }

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
        ])->paginate(10);

        return response()->json($articles);
    }
}
