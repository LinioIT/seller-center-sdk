<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Application;

class Configuration
{
    protected const VERSION = '1.0';

    protected const SOURCE = 'SDK';

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

    public function __construct(string $key, string $username, string $endpoint, string $version = self::VERSION, ?string $source = self::SOURCE)
    {
        $this->key = $key;
        $this->username = $username;
        $this->endpoint = $endpoint;
        $this->version = $version;
        $this->source = $source ?? self::SOURCE;
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
