<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Application\Security;

use Linio\SellerCenter\Exception\InvalidApiKeyException;

class Signature
{
    /**
     * @var string
     */
    protected $signature;

    private function __construct(string $signature)
    {
        $this->signature = $signature;
    }

    /**
     * @param string[] $parameters
     */
    public static function generate(array $parameters, string $apiKey): Signature
    {
        if (empty($apiKey)) {
            throw new InvalidApiKeyException();
        }

        $encoded = [];

        foreach ($parameters as $name => $value) {
            $encoded[] = rawurlencode($name) . '=' . rawurlencode($value);
        }

        $concatenatedParameters = implode('&', $encoded);

        $signature = rawurlencode(hash_hmac('sha256', $concatenatedParameters, $apiKey, false));

        return new self($signature);
    }

    public function get(): string
    {
        return $this->signature;
    }

    public function __toString(): string
    {
        return $this->signature;
    }
}
