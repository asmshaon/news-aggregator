<?php

declare(strict_types=1);

namespace App\Services\NewsApi;

use App\Entities\Article;
use App\Entities\Author;
use App\Entities\Category;
use App\Entities\Source;
use App\Services\NewsParserInterface;
use Carbon\Carbon;

/**
 * @class App\Services\NewsApi\NewsApiParser
 */
class NewsApiParser implements NewsParserInterface
{

    public function getArticle(array $data): Article
    {
        $article = new Article();

        $article->setTitle($data['title']);
        $article->setUrl($data['url']);
        $article->setImage($data['urlToImage']);
        $article->setDescription($data['description']);
        $article->setPublishedAt(Carbon::createFromDate($data['publishedAt']));

        return $article;
    }

    public function getAuthor(array $data): Author
    {
        $author = new Author();

        $author->setName($data['author']);

        return $author;
    }

    public function getSource(array $data): Source
    {
        $source = new Source();

        $source->setName($data['source']['name']);

        return $source;
    }

    public function getCategory(array $data): Category
    {
        $category = new Category();

        $category->setName($data['category']);

        return $category;
    }
}
