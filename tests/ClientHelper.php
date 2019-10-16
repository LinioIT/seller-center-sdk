<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Prophecy\Argument;

trait ClientHelper
{
    public function createClientWithResponse($body, $statusCode = 200)
    {
        $response = $this->prophesize(Response::class);

        $response
            ->getBody()
            ->willReturn($body);

        $response
            ->getStatusCode()
            ->willReturn($statusCode);

        $client = $this->prophesize(Client::class);
        $client->send(Argument::type(Request::class), Argument::type('array'))->willReturn($response);

        return $client->reveal();
    }
}
