<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class FailedToSaveArticle extends Exception
{
    /**
     *
     * @return void
     */
    public function __construct(?string $message = null)
    {
        parent::__construct(sprintf('Failed to save article due to %s.', $message));
    }
}
