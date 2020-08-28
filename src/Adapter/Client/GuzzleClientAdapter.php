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

        if ($guzzleVersion !== '6') {
            throw new Exception('Linio\'s SDK only supports Guzzle v6.');
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
    private function getGuzzleVersion($client): string
    {
        $guzzleClass = self::GUZZLE_CLASS;

        if (!is_subclass_of($client, $guzzleClass)) {
            return '';
        }

        if (!defined(sprintf('%s::VERSION', $guzzleClass))) {
            // @codeCoverageIgnoreStart
            return '';
            // @codeCoverageIgnoreEnd
        }

        return substr($guzzleClass::VERSION, 0, 1);
    }
}
