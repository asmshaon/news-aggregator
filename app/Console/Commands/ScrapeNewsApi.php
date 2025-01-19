<?php

namespace App\Console\Commands;

use App\Services\ApiService;
use App\Services\NewsApi\NewsApiParser;
use App\Services\NewsApi\NewsApiService;
use Illuminate\Console\Command;

class ScrapeNewsApi extends Command
{
    const PER_PAGE = 50;

    /** @var string */
    protected $signature = 'news-aggregator:news-api:scrape';

    /** @var string */
    protected $description = 'Scrape news articles from NewsAPI';

    /** @var string[] */
    protected array $categories = ['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'];

    public function __construct(protected NewsApiParser $newsApiParser, protected ApiService $apiService)
    {
        parent::__construct();
    }

    /**
     * @param NewsApiService $newsApiService
     * @return void
     */
    public function handle(NewsApiService $newsApiService): void
    {
        $this->info('Go! Started fetching articles from NewsAPI...');

        try {
            foreach ($this->categories as $category) {
                // TODO: use job to save articles
                $response = $newsApiService->getArticles([
                    'q'        => $category,
                    'language' => 'en',
                    'from'     => now()->subDay()->format('Y-m-d'),
                    'to'       => now()->format('Y-m-d'),
                    'sortBy'   => 'publishedAt',
                    'pageSize' => self::PER_PAGE,
                ]);

                if ($this->isValidResponse($response) === false) {
                    $this->info('Opps! Failed to fetch articles, please check logs for more details.');
                    return;
                }

                foreach ($response['articles'] as $data) {
                    try {
                        if (empty($data['author']) || $data['title'] === '[Removed]') {
                            continue;
                        }

                        $articleEntity = $this->newsApiParser->getArticle($data);
                        $authorEntity = $this->newsApiParser->getAuthor($data);
                        $sourceEntity = $this->newsApiParser->getSource($data);
                        $categoryEntity = $this->newsApiParser->getCategory(['category' => $category]);

                        $this->apiService->saveArticle($articleEntity,  $authorEntity, $categoryEntity, $sourceEntity);
                    } catch (\Exception $exception) {
                        $this->error('Error during scraping articles from NewsAPI: ' . $exception->getMessage());
                        continue; // continue to save next article
                    }
                }
            }
            $this->info('Boom! Done fetching articles from NewsAPI.');
        } catch (\Exception $exception) {
            $this->error('Error during scraping articles from NewsAPI: ' . $exception->getMessage());
        }
    }

    protected function isValidResponse(array $response): bool
    {
        try {
            return $response['status'] === 'ok' && $response['totalResults'] > 0 && !empty($response['articles']);
        } catch (\Exception $e) {
            $this->error('Error in response from NewsAPI: ' . $e->getMessage());
            return false;
        }
    }
}
