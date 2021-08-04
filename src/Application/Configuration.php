<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Application;

class Configuration
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string|null
     */
    private $source;

    public function __construct(string $key, string $username, string $endpoint, string $version = '1.0', ?string $source = 'SDK')
    {
        $this->key = $key;
        $this->username = $username;
        $this->endpoint = $endpoint;
        $this->version = $version ?? '1.0';
        $this->source = $source ?? 'SDK';
    }

    public function getUser(): string
    {
        return $this->username;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }
}
