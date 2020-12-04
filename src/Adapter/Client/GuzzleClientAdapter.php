<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Adapter\Client;

use Exception;
use Linio\SellerCenter\Contract\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleClientAdapter implements ClientInterface
{
    public const GUZZLE_CLASS = '\GuzzleHttp\ClientInterface';
    public const GUZZLE_SUPPORTED_VERSION = 6;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function __construct($client)
    {
        $guzzleVersion = $this->getGuzzleVersion($client);

        if ($guzzleVersion < self::GUZZLE_SUPPORTED_VERSION) {
            throw new Exception('Linio\'s SDK supports Guzzle v6 or greater');
        }

        $this->client = $client;
    }

    /**
     * @param mixed[] $options
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return $this->client->send($request, $options);
    }

    /**
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function getGuzzleVersion($client): int
    {
        $guzzleClass = self::GUZZLE_CLASS;

        if (!is_subclass_of($client, $guzzleClass)) {
            return 0;
        }

        if (!defined(sprintf('%s::VERSION', $guzzleClass))) {
            return 7;
        }

        return (int) substr($guzzleClass::VERSION, 0, 1);
    }
}
