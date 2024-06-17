<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Adapter\Client;

use Exception;
use GuzzleHttp\ClientInterface;
use Linio\SellerCenter\LinioTestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleClientAdapterTest extends LinioTestCase
{
    use ProphecyTrait;

    public function testItIsValidatingClass(): void
    {
        $this->expectException(Exception::class);

        $client = $this->prophesize(PsrClientInterface::class);
        new GuzzleClientAdapter($client->reveal());
    }

    public function testItIsSendingTheRequest(): void
    {
        $request = $this->prophesize(RequestInterface::class);
        $response = $this->prophesize(ResponseInterface::class);
        $options = [];

        $client = $this->prophesize(ClientInterface::class);
        $client
            ->send(Argument::exact($request->reveal()), Argument::exact($options))
            ->shouldBeCalled()
            ->willReturn($response);

        $clientAdapter = new GuzzleClientAdapter($client->reveal());
        $result = $clientAdapter->send($request->reveal(), $options);
        $this->assertSame($response->reveal(), $result);
    }
}
