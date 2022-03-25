<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use GuzzleHttp\Client;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait ClientHelper
{
    use ProphecyTrait;

    public function createClientWithResponse($body, $statusCode = 200)
    {
        $response = $this->prophesize(ResponseInterface::class);

        $response
            ->getBody()
            ->willReturn($body);

        $response
            ->getStatusCode()
            ->willReturn($statusCode);

        $client = $this->prophesize(Client::class);
        $client
            ->send(Argument::type(RequestInterface::class), Argument::type('array'))
            ->willReturn($response);

        return $client->reveal();
    }
}
