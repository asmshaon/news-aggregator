<?php

declare(strict_types=1);

namespace App\Services\NYTimesApi;

use App\Services\NewsServiceInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * @class App\Services\NYTimesApi\NYTimesApiService
 */
class NYTimesApiService implements NewsServiceInterface
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
        $this->apiKey = config('services.ny_times_api.api_key');
        $this->baseUrl = config('services.ny_times_api.base_url');
    }

    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    public function getArticles(array $query = []): array
    {
        $query['api-key'] = $this->apiKey;

        $response = Http::get($this->baseUrl . '/articlesearch.json', $query);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new \Exception('Opps! Failed to get articles from NY Times API');
        }
    }
}
