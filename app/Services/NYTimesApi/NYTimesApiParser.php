<?php

declare(strict_types=1);

namespace App\Services\NYTimesApi;

use App\Entities\Article;
use App\Entities\Author;
use App\Entities\Category;
use App\Entities\Source;
use App\Services\NewsParserInterface;
use Carbon\Carbon;

/**
 * @class App\Services\NYTimesApi\NYTimesApiParser
 */
class NYTimesApiParser implements NewsParserInterface
{

    public function getArticle(array $data): Article
    {
        $article = new Article();

        $article->setTitle($data['headline']['main']);
        $article->setUrl($data['web_url']);
        $article->setImage('https://www.nytimes.com/' . $data['multimedia'][0]['url']);
        $article->setDescription($data['abstract']);
        $article->setPublishedAt(Carbon::createFromDate($data['pub_date']));

        return $article;
    }

    public function getAuthor(array $data): Author
    {
        $author = new Author();

        if (isset($data['byline']['person'][0])) {
            $person = $data['byline']['person'][0];
            $author->setName($person['firstname'] . ' ' . $person['lastname']);
        } else {
            $author->setName('The New York Times');
        }

        return $author;
    }

    public function getSource(array $data): Source
    {
        $source = new Source();

        $source->setName($data['source']);

        return $source;
    }

    public function getCategory(array $data): Category
    {
        $category = new Category();

        $category->setName(strtolower($data['section_name']));

        return $category;
    }
}
