<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Application;

class Configuration
{
    protected const VERSION = '1.0';

    protected const SOURCE = 'SDK';

    protected const LANGUAGE = 'PHP';

    protected const SDK = 'FalabellaSDKPHP';

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

    /**
     * @var string|null
     */
    private $sellerId;

    /**
     * @var string|null
     */
    private $language;

    /**
     * @var string|null
     */
    private $languageVersion;

    /**
     * @var string|null
     */
    private $integrator;

    /**
     * @var string|null
     */
    private $country;

    public function __construct(
        string $key,
        string $username,
        string $endpoint,
        string $version = self::VERSION,
        ?string $source = self::SOURCE,
        ?string $sellerId = null,
        ?string $language = self::LANGUAGE,
        ?string $languageVersion = null,
        ?string $integrator = null,
        ?string $country = null
    ) {
        $this->key = $key;
        $this->username = $username;
        $this->endpoint = $endpoint;
        $this->version = $version;
        $this->source = $source ?? self::SOURCE;
        $this->sellerId = $sellerId;
        $this->language = $language ?? self::LANGUAGE;
        $this->languageVersion = $languageVersion ?? (string) phpversion();
        $this->integrator = $integrator;
        $this->country = $country;
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

    public function getSellerId(): ?string
    {
        return $this->sellerId;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getLanguageVersion(): ?string
    {
        return $this->languageVersion;
    }

    public function getIntegrator(): ?string
    {
        return $this->integrator;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getUserAgent(): ?string
    {
        return sprintf(
            '%s/%s/%s/%s/%s/%s',
            $this->getSellerId() ?? '',
            $this->getLanguage() ?? '',
            $this->getLanguageVersion() ?? '',
            $this->getIntegrator() ?? '',
            $this->getCountry() ?? '',
            self::SDK
        );
    }
}
