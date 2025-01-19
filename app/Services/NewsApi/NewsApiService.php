<?php

declare(strict_types=1);

namespace App\Services\NewsApi;

use App\Services\NewsServiceInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * @class App\Services\NewsApi\NewsApiService
 */
class NewsApiService implements NewsServiceInterface
{
    /** @var string */
    protected string $apiKey;

    /** @var string */
    protected string $baseUrl;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apiKey = config('services.news_api.api_key');
        $this->baseUrl = config('services.news_api.base_url');
    }

    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    public function getArticles(array $query = []): array
    {
        $response = Http::withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->baseUrl . '/everything', $query);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Opps! Failed to get articles from News API');
    }
}
