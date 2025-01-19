<?php

declare(strict_types=1);

namespace App\Entities;

class Author
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string|null
     */
    private ?string $profileUrl = null;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getProfileUrl(): ?string
    {
        return $this->profileUrl;
    }

    /**
     * @param string|null $profileUrl
     */
    public function setProfileUrl(?string $profileUrl): void
    {
        $this->profileUrl = $profileUrl;
    }
}
