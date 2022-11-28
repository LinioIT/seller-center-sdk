<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Application;

class Configuration
{
    protected const VERSION = '1.0';

    protected const SOURCE = 'SDK';

    protected const LANGUAGE = 'PHP';

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
    private $userId;

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

    public function __construct(string $key, string $username, string $endpoint, string $version = self::VERSION, ?string $source = self::SOURCE, ?string $userId = null, ?string $language = self::LANGUAGE, ?string $languageVersion = null, ?string $integrator = null, ?string $country = null)
    {
        $this->key = $key;
        $this->username = $username;
        $this->endpoint = $endpoint;
        $this->version = $version;
        $this->source = $source ?? self::SOURCE;
        $this->userId = $userId;
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

    public function getUserId(): ?string
    {
        return $this->userId;
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
        $userAgent = sprintf(
            '%s/%s/%s',
            $this->getUserId(),
            $this->getLanguage(),
            $this->getLanguageVersion()
        );

        if (!empty($this->getIntegrator())) {
            $userAgent = sprintf(
                '%s/%s',
                $userAgent,
                $this->getIntegrator()
            );
        }

        if (!empty($this->getCountry())) {
            $userAgent = sprintf(
                '%s/%s',
                $userAgent,
                $this->getCountry()
            );
        }

        return $userAgent;
    }
}
