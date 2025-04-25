<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use GuzzleHttp\Client;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

trait ClientHelper
{
    use ProphecyTrait;

    public function createClientWithResponse(
        string $body,
        int $statusCode = 200,
        ?string $extraResponseBody = null,
        int $extraStatusCode = 200,
    ) {
        $response = $this->prophesize(ResponseInterface::class);
        $bodyStream = $this->prophesize(StreamInterface::class);
        $response
            ->getBody()
            ->willReturn($bodyStream->reveal());

        $bodyStream->__toString()
            ->willReturn($body);

        $response
            ->getStatusCode()
            ->willReturn($statusCode);

        $client = $this->prophesize(Client::class);

        $client
            ->send(Argument::type(RequestInterface::class), Argument::type('array'))
            ->willReturn($response);

        if (!empty($extraResponseBody)) {
            $extraResponse = $this->prophesize(ResponseInterface::class);
            $extraResponse
                ->getBody()
                ->willReturn($bodyStream->reveal());

            $bodyStream->__toString()
                ->willReturn($extraResponseBody);

            $extraResponse
                ->getStatusCode()
                ->willReturn($extraStatusCode);

            $client
                ->send(Argument::type(RequestInterface::class), Argument::type('array'))
                ->willReturn($extraResponse, $response);
        }

        return $client->reveal();
    }
}
