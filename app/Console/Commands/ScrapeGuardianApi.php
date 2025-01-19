<?php

namespace App\Console\Commands;

use App\Services\ApiService;
use App\Services\GuardianApi\GuardianApiParser;
use App\Services\GuardianApi\GuardianApiService;
use Illuminate\Console\Command;

class ScrapeGuardianApi extends Command
{
    const PER_PAGE = 50;

    /** @var string */
    protected $signature = 'news-aggregator:guardian-api:scrape';

    /** @var string */
    protected $description = 'Scrape news articles from Guardian API';

    /** @var string[] */
    protected array $categories = ['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'];

    public function __construct(protected GuardianApiParser $guardianApiParser, protected ApiService $apiService)
    {
        parent::__construct();
    }

    /**
     * @param GuardianApiService $guardianApiService
     * @return void
     */
    public function handle(GuardianApiService $guardianApiService): void
    {
        $this->info('Go! Started fetching articles from Guardian API...');

        try {
            foreach ($this->categories as $category) {
                // TODO: use job to save articles
                $response = $guardianApiService->getArticles([
                    'q'           => $category,
                    'from-date'   => now()->subDay()->format('Y-m-d'),
                    'to-date'     => now()->format('Y-m-d'),
                    'order-by'    => 'newest',
                    'page-size'   => self::PER_PAGE,
                    'show-fields' => 'trailText,byline,thumbnail'
                ]);

                if ($this->isValidResponse($response) === false) {
                    $this->info('Opps! Failed to fetch articles, please check logs for more details.');
                    return;
                }

                foreach ($response['response']['results'] as $data) {
                    try {
                        $articleEntity = $this->guardianApiParser->getArticle($data);
                        $authorEntity = $this->guardianApiParser->getAuthor($data);
                        $sourceEntity = $this->guardianApiParser->getSource($data);
                        $categoryEntity = $this->guardianApiParser->getCategory($data);

                        $this->apiService->saveArticle($articleEntity,  $authorEntity, $categoryEntity, $sourceEntity);
                    } catch (\Exception $exception) {
                        $this->error('Error during scraping articles from Guardian API: ' . $exception->getMessage());
                        continue; // continue to save next article
                    }
                }
            }
            $this->info('Boom! Done fetching articles from Guardian API.');
        } catch (\Exception $exception) {
            $this->error('Error during scraping articles from Guardian API: ' . $exception->getMessage());
        }
    }

    protected function isValidResponse(array $response): bool
    {
        try {
            return $response['response']['status'] === 'ok' && $response['response']['total'] > 0 && !empty($response['response']['results']);
        } catch (\Exception $e) {
            $this->error('Error in response from Guardian API: ' . $e->getMessage());
            return false;
        }
    }
}
