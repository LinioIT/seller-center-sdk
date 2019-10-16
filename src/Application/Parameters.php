<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Application;

use DateTimeImmutable;

class Parameters
{
    /**
     * @var array
     */
    protected $parameters = [];

    public function set(array $parameters): void
    {
        $this->parameters = array_merge($this->parameters, $parameters);
        ksort($this->parameters);
    }

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
}
