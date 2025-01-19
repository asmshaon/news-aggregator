<?php

declare(strict_types=1);

namespace App\Services;

interface NewsServiceInterface
{
    public function getArticles(array $query): array;
}
