<?php

namespace App\Console\Commands;

use App\Services\ApiService;
use App\Services\NYTimesApi\NYTimesApiParser;
use App\Services\NYTimesApi\NYTimesApiService;
use Illuminate\Console\Command;

class ScrapeNYTimesApi extends Command
{
    const PER_PAGE = 50;

    /** @var string */
    protected $signature = 'news-aggregator:ny-times-api:scrape';

    /** @var string */
    protected $description = 'Scrape news articles from NY Times API';

    /** @var string[] */
    protected array $categories = ['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'];

    public function __construct(protected NYTimesApiParser $NYTimesApiParser, protected ApiService $apiService)
    {
        parent::__construct();
    }

    /**
     * @param NYTimesApiService $NYTimesApiService
     * @return void
     */
    public function handle(NYTimesApiService $NYTimesApiService): void
    {
        $this->info('Go! Started fetching articles from NY Times API...');

        try {
            foreach ($this->categories as $category) {
                // TODO: use job to save articles
                $response = $NYTimesApiService->getArticles([
                    'q'           => $category,
                    'begin_date'   => now()->subDay()->format('Ymd'),
                    'end_date'     => now()->format('Ymd'),
                    'sort'    => 'newest',
                ]);

                if ($this->isValidResponse($response) === false) {
                    $this->info('Opps! Failed to fetch articles, please check logs for more details.');
                    return;
                }

                foreach ($response['response']['docs'] as $data) {
                    try {
                        $articleEntity = $this->NYTimesApiParser->getArticle($data);
                        $authorEntity = $this->NYTimesApiParser->getAuthor($data);
                        $sourceEntity = $this->NYTimesApiParser->getSource($data);
                        $categoryEntity = $this->NYTimesApiParser->getCategory($data);

                        $this->apiService->saveArticle($articleEntity,  $authorEntity, $categoryEntity, $sourceEntity);
                    } catch (\Exception $exception) {
                        $this->error('Error during scraping articles from NY Times API: ' . $exception->getMessage());
                        continue; // continue to save next article
                    }
                }

                sleep(30); // free account has Rate limit, so waiting 30 secs between requests
            }
            $this->info('Boom! Done fetching articles from NY Times API.');
        } catch (\Exception $exception) {
            $this->error('Error during scraping articles from NY Times API: ' . $exception->getMessage());
        }
    }

    protected function isValidResponse(array $response): bool
    {
        try {
            return $response['status'] === 'OK' && $response['response']['meta']['hits'] > 0 && !empty($response['response']['docs']);
        } catch (\Exception $e) {
            $this->error('Error in response from NY Times API: ' . $e->getMessage());
            return false;
        }
    }
}
