<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Adapter\Client;

use Linio\SellerCenter\LinioTestCase;
use Nyholm\Psr7\Uri;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class PsrClientAdapterTest extends LinioTestCase
{
    use ProphecyTrait;

    /**
     * @var ObjectProphecy
     */
    private $client;

    /**
     * @var PsrClientAdapter
     */
    private $clientAdapter;

    public function setUp(): void
    {
        $this->client = $this->prophesize(ClientInterface::class);
        $this->clientAdapter = new PsrClientAdapter($this->client->reveal());
    }

    public function testItIsAppendingTheQuery(): void
    {
        $query = [
            'foo' => 'bar',
            'foobar' => 'foobarbaz',
        ];

        $uri = new Uri('https://linio.com');
        $expected = new Uri('https://linio.com?foo=bar&foobar=foobarbaz');

        $result = $this->clientAdapter->appendQuery($uri, $query);
        $this->assertEquals($expected, $result);
    }

    public function testItReturnsTheSameUriWithEmptyQuery(): void
    {
        $uri = new Uri('https://linio.com');

        $result = $this->clientAdapter->appendQuery($uri, []);
        $this->assertSame($uri, $result);
    }

    public function testItSendsTheRequest(): void
    {
        $updatedRequest = $this->prophesize(RequestInterface::class);

        $request = $this->prophesize(RequestInterface::class);
        $request
            ->getUri()
            ->shouldBeCalled()
            ->willReturn(new Uri());
        $request
            ->withUri(Argument::type(UriInterface::class))
            ->shouldBeCalled()
            ->willReturn($updatedRequest);

        $this->client
            ->sendRequest(Argument::exact($updatedRequest))
            ->shouldBeCalled();

        $this->clientAdapter->send($request->reveal(), ['query' => [1]]);
    }
}
