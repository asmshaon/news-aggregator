<?php

namespace App\Console\Commands;

use App\Services\ApiService;
use App\Services\GuardianApi\GuardianApiParser;
use App\Services\GuardianApi\GuardianApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ScrapeAllNews extends Command
{
    /** @var string */
    protected $signature = 'news-aggregator:scrape-all';

    /** @var string */
    protected $description = 'Scrape all news articles from different API';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info('The process has started. It may take 2-3 minutes to complete. Please wait until it finishes.');

        $this->info('Go! Started fetching articles from NewsAPI...');
        Artisan::call('news-aggregator:news-api:scrape');
        $this->info('Boom! Done fetching articles from NewsAPI.');

        $this->info('Go! Started fetching articles from Guardian API...');
        Artisan::call('news-aggregator:guardian-api:scrape');
        $this->info('Boom! Done fetching articles from Guardian API.');

        $this->info('Go! Started fetching articles from NY Times API...');
        Artisan::call('news-aggregator:ny-times-api:scrape');
        $this->info('Boom! Done fetching articles from NY Times API.');

        $this->info('Process finished successfully!');
    }
}
