<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;

class ArticleRepository
{
    /**
     * @param \App\Entities\Article $article
     * @return Article
     */
    public function saveArticle(\App\Entities\Article $article): Article
    {
        return Article::updateOrCreate(
            ['url' => $article->getUrl()],
            [
                'title'        => $article->getTitle(),
                'image'        => $article->getImage(),
                'description'  => $article->getDescription(),
                'source_id'    => $article->getSourceId(),
                'author_id'    => $article->getAuthorId(),
                'category_id'  => $article->getCategoryId(),
                'published_at' => $article->getPublishedAt(),
            ]
        );
    }

    /**
     * @param \App\Entities\Author $author
     * @return Author
     */
    public function saveAuthor(\App\Entities\Author $author): Author
    {
        return Author::updateOrCreate(
            ['name' => $author->getName()],
            [
                'profile_url' => $author->getProfileUrl(),
            ]
        );
    }

    /**
     * @param \App\Entities\Source $source
     * @return Source
     */
    public function saveSource(\App\Entities\Source $source): Source
    {
        return Source::updateOrCreate(
            ['name' => $source->getName()],
            [
                'url' => $source->getUrl(),
            ]
        );
    }

    /**
     * @param \App\Entities\Category $category
     * @return Category
     */
    public function saveCategory(\App\Entities\Category $category): Category
    {
        return Category::updateOrCreate(
            ['name' => $category->getName()]
        );
    }
}
