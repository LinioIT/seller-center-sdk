<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Application;

use DateTimeImmutable;

class Parameters
{
    /**
     * @var mixed[] array
     */
    protected $parameters = [];

    /**
     * @param mixed[] $parameters
     */
    public function set(array $parameters): void
    {
        $this->parameters = array_merge($this->parameters, $parameters);
        ksort($this->parameters);
    }

    /**
     * @return mixed[] $parameters
     */
    public function all(): array
    {
        $parameters = $this->parameters;

        foreach ($parameters as $key => $value) {
            $parameters[$key] = (string) $value;
        }

        return $parameters;
    }

    public static function fromBasics(string $user, string $version, string $format = 'XML'): Parameters
    {
        $parameters = new Parameters();

        $parameters->set([
            'Timestamp' => (new DateTimeImmutable())->format(DATE_ATOM),
            'UserID' => $user,
            'Version' => $version,
            'Format' => $format,
        ]);

        return $parameters;
    }

    public static function fromConfiguration(Configuration $configuration): Parameters
    {
        $parameters = new Parameters();

        $userAgent = sprintf(
            '%s/%s/%s',
            $configuration->getUserId(),
            $configuration->getLanguage(),
            $configuration->getLanguageVersion()
        );

        if(!empty($configuration->getIntegrator())){
            $userAgent = sprintf(
                '%s/%s',
                $userAgent,
                $configuration->getIntegrator()
            );
        }

        if(!empty($configuration->getCountry())){
            $userAgent = sprintf(
                '%s/%s',
                $userAgent,
                $configuration->getCountry()
            );
        }

        $parameters->set([
            'Timestamp' => (new DateTimeImmutable())->format(DATE_ATOM),
            'UserID' => $configuration->getUser(),
            'Version' => $configuration->getVersion(),
            'Format' => 'XML',
            'User-Agent' => $userAgent,
        ]);

        return $parameters;
    }
}
