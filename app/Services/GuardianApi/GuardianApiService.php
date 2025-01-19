<?php

declare(strict_types=1);

namespace App\Services\GuardianApi;

use App\Services\NewsServiceInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * @class App\Services\GuardianApi\GuardianApiService
 */
class GuardianApiService implements NewsServiceInterface
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
        $this->apiKey = config('services.guardian_api.api_key');
        $this->baseUrl = config('services.guardian_api.base_url');
    }

    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    public function getArticles(array $query = []): array
    {
        $query['api-key'] = $this->apiKey;
        $response = Http::get($this->baseUrl . '/search', $query);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Opps! Failed to get articles from Guardian API');
    }
}
