<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Support\Collection;

class AuthorRepository
{
    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Article::all();
    }

    /**
     * @param array $articles
     * @return bool
     */
    public function saveArticles(array $articles): bool
    {
        foreach ($articles as $article) {
            if(empty($article['author']) || $article['title'] === '[Removed]') {
                continue;
            }

            Article::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title'        => $article['title'],
                    'description'  => $article['description'],
                    'content'      => $article['content'],
                    'author'       => $article['author'],
                    'source'       => $article['source']['name'],
                    'published_at' => $article['publishedAt'],
                ]
            );
        }

        return true;
    }
}
