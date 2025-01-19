<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Article;
use App\Entities\Author;
use App\Entities\Category;
use App\Entities\Source;
use App\Repositories\ArticleRepository;

class ApiService
{
    public function __construct(protected ArticleRepository $articleRepository)
    {

    }

    public function saveArticle(Article $articleEntity, Author $authorEntity, Category $categoryEntity, Source $sourceEntity): \App\Models\Article
    {
        $authorModel = $this->articleRepository->saveAuthor($authorEntity);
        $categoryModel = $this->articleRepository->saveCategory($categoryEntity);
        $sourceModel = $this->articleRepository->saveSource($sourceEntity);

        $articleEntity->setAuthorId($authorModel->id);
        $articleEntity->setCategoryId($categoryModel->id);
        $articleEntity->setSourceId($sourceModel->id);

        return $this->articleRepository->saveArticle($articleEntity);
    }
}
