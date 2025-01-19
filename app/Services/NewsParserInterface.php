<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Article;
use App\Entities\Author;
use App\Entities\Category;
use App\Entities\Source;

interface NewsParserInterface
{
    public function getArticle(array $data): Article;

    public function getAuthor(array $data): Author;

    public function getSource(array $data): Source;

    public function getCategory(array $data): Category;
}
