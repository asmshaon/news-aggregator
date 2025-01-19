<?php

declare(strict_types=1);

namespace App\Services\GuardianApi;

use App\Entities\Article;
use App\Entities\Author;
use App\Entities\Category;
use App\Entities\Source;
use App\Services\NewsParserInterface;
use Carbon\Carbon;

/**
 * @class App\Services\GuardianApi\GuardianApiParser
 */
class GuardianApiParser implements NewsParserInterface
{

    public function getArticle(array $data): Article
    {
        $article = new Article();

        $article->setTitle($data['webTitle']);
        $article->setUrl($data['webUrl']);
        $article->setImage($data['fields']['thumbnail']);
        $article->setDescription($data['fields']['trailText']);
        $article->setPublishedAt(Carbon::createFromDate($data['webPublicationDate']));

        return $article;
    }

    public function getAuthor(array $data): Author
    {
        $author = new Author();

        $author->setName($data['fields']['byline'] ?? 'The Guardian');

        return $author;
    }

    public function getSource(array $data): Source
    {
        $source = new Source();

        $source->setName('The Guardian');

        return $source;
    }

    public function getCategory(array $data): Category
    {
        $category = new Category();

        $category->setName(strtolower($data['sectionName']));

        return $category;
    }
}
