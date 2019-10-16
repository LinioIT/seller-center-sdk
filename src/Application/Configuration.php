<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Application;

use GuzzleHttp\Psr7\Uri;

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

    public function __construct(string $key, string $username, string $endpoint, string $version = '1.0')
    {
        $this->key = $key;
        $this->username = $username;
        $this->endpoint = $endpoint;
        $this->version = $version ?? '1.0';
    }

    public function getUser(): string
    {
        return $this->username;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getEndpoint(): Uri
    {
        return new Uri($this->endpoint);
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
